<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use App\Notifications\ApplicationStatusNotification;
use App\Mail\InterviewScheduled;
use App\Mail\InterviewCancelled;
use App\Mail\InterviewRescheduled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class HRInterviewController extends Controller
{
    /**
     * Display list of HR interviews assigned to the authenticated HR user (or all if admin).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isHR = $user->role === 'hr';

        $query = Interview::with(['application.user', 'application.job', 'interviewer'])
            ->where('type', 'hr');

        if ($isHR) {
            $query->where('interviewer_id', $user->id);
        }

        // Timeline and status filtering
        $filter = $request->get('filter', $request->get('status', 'all'));

        if ($filter === 'today') {
            $query->whereDate('interview_date', today());
        } elseif ($filter === 'upcoming') {
            $query->where('interview_date', '>=', today())
                  ->where('status', 'scheduled');
        } elseif ($filter === 'completed') {
            $query->where('status', 'completed');
        } elseif ($filter === 'cancelled') {
            $query->where('status', 'cancelled');
        } elseif ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort upcoming first by nearest date, else latest
        if (in_array($filter, ['today', 'upcoming'])) {
            $interviews = $query->orderBy('interview_date', 'asc')->orderBy('start_time', 'asc')->paginate(10)->withQueryString();
        } else {
            $interviews = $query->latest('interview_date')->paginate(10)->withQueryString();
        }

        $baseCountQuery = Interview::where('type', 'hr');
        if ($isHR) {
            $baseCountQuery->where('interviewer_id', $user->id);
        }

        $metrics = [
            'total' => (clone $baseCountQuery)->count(),
            'today' => (clone $baseCountQuery)->whereDate('interview_date', today())->count(),
            'upcoming' => (clone $baseCountQuery)->where('interview_date', '>=', today())->where('status', 'scheduled')->count(),
            'completed' => (clone $baseCountQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseCountQuery)->where('status', 'cancelled')->count(),
        ];

        return view('hr.interviews.index', compact('interviews', 'metrics', 'filter'));
    }

    /**
     * Show detailed view of an assigned HR interview.
     */
    public function show(Interview $interview)
    {
        $user = auth()->user();

        // Strict Authorization
        if ($user->role === 'hr') {
            if ($interview->type !== 'hr' || $interview->interviewer_id !== $user->id) {
                abort(403, 'Unauthorized access: You can only view HR interviews assigned to you.');
            }
        }

        $interview->load([
            'application.user',
            'application.job',
            'application.resume',
            'interviewer',
        ]);

        $application = $interview->application;

        return view('hr.interviews.show', compact('interview', 'application'));
    }

    /**
     * Show HR interview schedule form.
     */
    public function create(Application $application)
    {
        $application->load(['user', 'job', 'hrInterview']);

        return view('hr.interviews.create', compact('application'));
    }

    /**
     * Store scheduled HR interview.
     */
    public function store(Request $request, Application $application)
    {
        $validated = $request->validate([
            'interview_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'meeting_link' => 'required|url|max:2048',
            'notes' => 'nullable|string|max:5000',
        ]);

        if ($application->status === 'rejected') {
            return back()->with('error', 'A rejected application cannot have an interview scheduled.');
        }

        $existingInterview = $application->hrInterview;
        $isReschedule = $existingInterview && $existingInterview->status === 'scheduled';

        $interview = Interview::updateOrCreate(
            [
                'application_id' => $application->id,
                'type' => 'hr',
            ],
            array_merge($validated, [
                'status' => 'scheduled',
                'result' => 'pending',
                'interviewer_id' => auth()->id(),
            ])
        );

        $application->update(['status' => 'interview']);

        $application->user->notify(
            new ApplicationStatusNotification(
                $isReschedule ? 'HR Interview Rescheduled' : 'HR Interview Scheduled',
                $isReschedule
                    ? "Your HR interview for {$application->job->title} has been rescheduled to {$interview->interview_date->format('d M Y')}."
                    : "Your HR interview for {$application->job->title} has been scheduled for {$interview->interview_date->format('d M Y')}.",
                'interview'
            )
        );

        try {
            $interview->load(['application.user', 'application.job']);
            if ($isReschedule) {
                Mail::to($application->user->email)->send(new InterviewRescheduled($interview));
            } else {
                Mail::to($application->user->email)->send(new InterviewScheduled($interview));
            }
        } catch (\Throwable $e) {}

        return redirect()->route('hr.applications.show', $application)
            ->with('success', 'HR interview scheduled successfully.');
    }

    /**
     * Complete HR interview, submit recommendation (PASS / FAIL), and notify Admin.
     */
    public function complete(Request $request, Application $application)
    {
        $validated = $request->validate([
            'result' => 'nullable|in:passed,failed',
            'admin_feedback' => 'nullable|string|max:5000',
            'feedback_attachment' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $result = $validated['result'] ?? 'passed';

        $interview = $application->hrInterview ?? $application->interview;
        if (!$interview) {
            return back()->with('error', 'No HR interview found for this application.');
        }

        // Strict Authorization
        if (auth()->user()->role === 'hr') {
            if ($interview->type !== 'hr' || ($interview->interviewer_id && $interview->interviewer_id !== auth()->id())) {
                abort(403, 'Unauthorized action: You can only complete HR interviews assigned to you.');
            }
        }

        $attachmentPath = $interview->feedback_attachment_path;
        if ($request->hasFile('feedback_attachment')) {
            $path = $request->file('feedback_attachment')->store('interview_feedback', 'public');
            $attachmentPath = $path;
        }

        $interview->update([
            'status' => 'completed',
            'result' => $result,
            'admin_feedback' => $validated['admin_feedback'] ?? null,
            'feedback_attachment_path' => $attachmentPath,
            'feedback_submitted_at' => now(),
        ]);

        // Advance Application Status
        if ($result === 'failed') {
            $application->update(['status' => 'rejected']);
            $application->user->notify(
                new ApplicationStatusNotification(
                    'Application Status Update',
                    "Thank you for interviewing for {$application->job->title}. Unfortunately, we will not be moving forward.",
                    'rejected'
                )
            );
        } else {
            if ($application->requiresTechnicalInterview()) {
                $application->update(['status' => 'technical_interview']);
                $application->user->notify(
                    new ApplicationStatusNotification(
                        'HR Round Cleared',
                        "You have successfully cleared the HR round for {$application->job->title}! Technical interview will be scheduled shortly.",
                        'interview'
                    )
                );
            } else {
                $application->update(['status' => 'admin_review']);
                $application->user->notify(
                    new ApplicationStatusNotification(
                        'HR Round Cleared',
                        "You have successfully cleared the HR round for {$application->job->title}! Your profile is in Final Review.",
                        'interview'
                    )
                );
            }
        }

        // Notify All Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new ApplicationStatusNotification(
                    'HR Interview Completed',
                    "HR interview for {$application->user->name} completed by " . auth()->user()->name . " with recommendation: " . strtoupper($result) . '.',
                    'interview'
                )
            );
        }

        return back()->with('success', 'HR interview completed and evaluation recommendation recorded.');
    }

    /**
     * Download evaluation attachment.
     */
    public function downloadAttachment(Application $application)
    {
        $interview = $application->hrInterview ?? $application->interview;
        if (!$interview || !$interview->feedback_attachment_path) {
            return back()->with('error', 'Evaluation attachment not found.');
        }

        // Strict Authorization
        if (auth()->user()->role === 'hr') {
            if ($interview->type !== 'hr' || ($interview->interviewer_id && $interview->interviewer_id !== auth()->id())) {
                abort(403, 'Unauthorized access to this attachment.');
            }
        }

        if (!Storage::disk('public')->exists($interview->feedback_attachment_path)) {
            return back()->with('error', 'Attachment file not found on storage.');
        }

        return Storage::disk('public')->download($interview->feedback_attachment_path);
    }
}
