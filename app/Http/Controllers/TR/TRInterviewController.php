<?php

namespace App\Http\Controllers\TR;

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

class TRInterviewController extends Controller
{
    /**
     * Display list of technical interviews assigned to the authenticated TR user (or all if admin).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isTR = $user->role === 'tr';

        $query = Interview::with(['application.user', 'application.job', 'interviewer'])
            ->where('type', 'technical');

        if ($isTR) {
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

        if (in_array($filter, ['today', 'upcoming'])) {
            $interviews = $query->orderBy('interview_date', 'asc')->orderBy('start_time', 'asc')->paginate(10)->withQueryString();
        } else {
            $interviews = $query->latest('interview_date')->paginate(10)->withQueryString();
        }

        $baseCountQuery = Interview::where('type', 'technical');
        if ($isTR) {
            $baseCountQuery->where('interviewer_id', $user->id);
        }

        $metrics = [
            'total' => (clone $baseCountQuery)->count(),
            'today' => (clone $baseCountQuery)->whereDate('interview_date', today())->count(),
            'upcoming' => (clone $baseCountQuery)->where('interview_date', '>=', today())->where('status', 'scheduled')->count(),
            'completed' => (clone $baseCountQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseCountQuery)->where('status', 'cancelled')->count(),
        ];

        return view('tr.interviews.index', compact('interviews', 'metrics', 'filter'));
    }

    /**
     * Show detailed view of an assigned technical interview.
     */
    public function show(Interview $interview)
    {
        $user = auth()->user();

        // Strict Authorization
        if ($user->role === 'tr') {
            if ($interview->type !== 'technical' || $interview->interviewer_id !== $user->id) {
                abort(403, 'Unauthorized access: You can only view technical interviews assigned to you.');
            }
        }

        $interview->load([
            'application.user',
            'application.job',
            'application.resume',
            'application.hrInterview',
            'interviewer',
        ]);

        $application = $interview->application;

        return view('tr.interviews.show', compact('interview', 'application'));
    }

    /**
     * Show interview schedule form for technical round.
     */
    public function create(Application $application)
    {
        $application->load(['user', 'job', 'hrInterview', 'technicalInterview']);

        return view('tr.interviews.create', compact('application'));
    }

    /**
     * Store scheduled technical interview.
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
            return back()->with('error', 'A rejected candidate cannot have a technical interview scheduled.');
        }

        $existingInterview = $application->technicalInterview;
        $isReschedule = $existingInterview && $existingInterview->status === 'scheduled';

        $interview = Interview::updateOrCreate(
            [
                'application_id' => $application->id,
                'type' => 'technical',
            ],
            array_merge($validated, [
                'status' => 'scheduled',
                'result' => 'pending',
                'interviewer_id' => auth()->id(),
            ])
        );

        $application->update(['status' => 'technical_interview']);

        $application->user->notify(
            new ApplicationStatusNotification(
                $isReschedule ? 'Technical Interview Rescheduled' : 'Technical Interview Scheduled',
                $isReschedule
                    ? "Your technical interview for {$application->job->title} has been rescheduled to {$interview->interview_date->format('d M Y')}."
                    : "Your technical interview for {$application->job->title} has been scheduled for {$interview->interview_date->format('d M Y')}.",
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

        return redirect()->route('tr.applications.show', $application)
            ->with('success', 'Technical interview scheduled successfully.');
    }

    /**
     * Complete technical interview and submit evaluation notes/attachments (PASS / FAIL).
     */
    public function complete(Request $request, Application $application)
    {
        $validated = $request->validate([
            'result' => 'nullable|in:passed,failed',
            'admin_feedback' => 'nullable|string|max:5000',
            'feedback_attachment' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:10240',
        ]);

        $result = $validated['result'] ?? 'passed';

        $interview = $application->technicalInterview ?? $application->interview;
        if (!$interview) {
            return back()->with('error', 'No technical interview found for this application.');
        }

        // Strict Authorization
        if (auth()->user()->role === 'tr') {
            if ($interview->type !== 'technical' || ($interview->interviewer_id && $interview->interviewer_id !== auth()->id())) {
                abort(403, 'Unauthorized action: You can only complete technical interviews assigned to you.');
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
            $application->update(['status' => 'admin_review']);
            $application->user->notify(
                new ApplicationStatusNotification(
                    'Technical Evaluation Cleared',
                    "Congratulations! You have passed the technical assessment for {$application->job->title}! Your application is currently under final review.",
                    'interview'
                )
            );
        }

        // Notify All Admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new ApplicationStatusNotification(
                    'Technical Interview Completed',
                    "Technical interview for {$application->user->name} completed by " . auth()->user()->name . " with recommendation: " . strtoupper($result) . '.',
                    'interview'
                )
            );
        }

        return back()->with('success', 'Technical evaluation completed and recommendation recorded.');
    }

    /**
     * Download evaluation attachment.
     */
    public function downloadAttachment(Application $application)
    {
        $interview = $application->technicalInterview ?? $application->interview;
        if (!$interview || !$interview->feedback_attachment_path) {
            return back()->with('error', 'Evaluation attachment not found.');
        }

        // Strict Authorization
        if (auth()->user()->role === 'tr') {
            if ($interview->type !== 'technical' || ($interview->interviewer_id && $interview->interviewer_id !== auth()->id())) {
                abort(403, 'Unauthorized access to this attachment.');
            }
        }

        if (!Storage::disk('public')->exists($interview->feedback_attachment_path)) {
            return back()->with('error', 'Attachment file not found on storage.');
        }

        return Storage::disk('public')->download($interview->feedback_attachment_path);
    }
}
