<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\InterviewScheduled;
use App\Mail\InterviewCancelled;
use App\Mail\InterviewRescheduled;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ApplicationStatusNotification;

class InterviewController extends Controller
{
    /**
     * Show interview scheduling form.
     */
    public function create(Request $request, Application $application)
    {
        $application->load([
            'user',
            'job',
            'interviews.interviewer',
            'hrInterview.interviewer',
            'technicalInterview.interviewer',
        ]);

        $type = $request->query('type');
        if (!$type) {
            $type = ($application->hasHRInterviewPassed() && $application->requiresTechnicalInterview()) ? 'technical' : 'hr';
        }

        $hrInterviewers = User::where('role', 'hr')->orderBy('name')->get(['id', 'name', 'email']);
        $trInterviewers = User::where('role', 'tr')->orderBy('name')->get(['id', 'name', 'email']);

        $targetInterview = $type === 'technical' ? $application->technicalInterview : $application->hrInterview;

        return view(
            'admin.interviews.create',
            compact('application', 'type', 'hrInterviewers', 'trInterviewers', 'targetInterview')
        );
    }

    /**
     * Schedule or reschedule interview (HR or Technical) with explicit interviewer assignment.
     */
    public function store(Request $request, Application $application)
    {
        $validated = $request->validate([
            'type' => [
                'nullable',
                'in:hr,technical',
            ],
            'interviewer_id' => [
                'nullable',
                'exists:users,id',
            ],
            'interview_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'start_time' => [
                'required',
                'date_format:H:i',
            ],
            'end_time' => [
                'required',
                'date_format:H:i',
                'after:start_time',
            ],
            'meeting_link' => [
                'required',
                'url',
                'max:2048',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        if ($application->status === 'rejected') {
            return back()->with(
                'error',
                'A rejected application cannot have an interview scheduled.'
            );
        }

        // Determine interview type: explicit or automatically based on current candidate state
        $type = $validated['type'] ?? (($application->hasHRInterviewPassed() && $application->requiresTechnicalInterview()) ? 'technical' : 'hr');

        // Check if candidate is at appropriate stage
        if ($type === 'hr' && !in_array($application->status, ['shortlisted', 'hr_interview', 'interview'])) {
            return back()->with(
                'error',
                'The candidate must be shortlisted before scheduling an HR interview.'
            );
        }

        if ($type === 'technical' && !$application->canScheduleTechnicalInterview() && $application->status !== 'technical_interview') {
            return back()->with(
                'error',
                'The candidate must pass the HR interview first and the job must require a technical interview.'
            );
        }

        $existingInterview = Interview::where('application_id', $application->id)
            ->where('type', $type)
            ->first();

        if ($existingInterview && $existingInterview->status === 'completed') {
            return back()->with(
                'error',
                'A completed interview cannot be rescheduled.'
            );
        }

        // Determine Interviewer with strict role verification
        $interviewerId = $validated['interviewer_id'] ?? ($existingInterview ? $existingInterview->interviewer_id : null);

        if (!$interviewerId) {
            $defaultUser = $type === 'technical'
                ? User::where('role', 'tr')->first()
                : User::where('role', 'hr')->first();
            $interviewerId = $defaultUser?->id ?? auth()->id();
        }

        $assignedUser = User::find($interviewerId);
        if (!$assignedUser) {
            return back()->withErrors([
                'interviewer_id' => 'Selected interviewer could not be found.',
            ])->withInput();
        }

        if ($type === 'hr' && $assignedUser->role !== 'hr' && $assignedUser->role !== 'admin') {
            return back()->withErrors([
                'interviewer_id' => 'Unauthorized assignment: Only users with the HR role can be assigned to HR interviews.',
            ])->withInput();
        }

        if ($type === 'technical' && $assignedUser->role !== 'tr' && $assignedUser->role !== 'admin') {
            return back()->withErrors([
                'interviewer_id' => 'Unauthorized assignment: Only users with the Technical Recruiter (TR) role can be assigned to Technical interviews.',
            ])->withInput();
        }

        $isReschedule = $existingInterview && $existingInterview->status === 'scheduled';

        $interview = Interview::updateOrCreate(
            [
                'application_id' => $application->id,
                'type' => $type,
            ],
            [
                'interview_date' => $validated['interview_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'meeting_link' => $validated['meeting_link'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'scheduled',
                'result' => 'pending',
                'interviewer_id' => $assignedUser->id,
            ]
        );

        $application->update([
            'status' => $type === 'technical' ? 'technical_interview' : 'interview',
        ]);

        $roundLabel = $type === 'technical' ? 'Technical Interview' : 'HR Interview';

        // Notify Assigned Interviewer
        $assignedUser->notify(
            new ApplicationStatusNotification(
                $isReschedule ? "{$roundLabel} Assignment Updated" : "New {$roundLabel} Assigned",
                "You have been assigned to interview {$application->user->name} for {$application->job->title} on {$interview->interview_date->format('d M Y')}.",
                'interview'
            )
        );

        // Notify Candidate
        $application->user->notify(
            new ApplicationStatusNotification(
                $isReschedule ? "{$roundLabel} Rescheduled" : "{$roundLabel} Scheduled",
                $isReschedule
                    ? "Your {$roundLabel} for " . $application->job->title . ' has been rescheduled.'
                    : "Your {$roundLabel} for " . $application->job->title . ' has been scheduled.',
                'interview'
            )
        );

        $interview->load([
            'application.user',
            'application.job',
            'interviewer',
        ]);

        try {
            if ($isReschedule) {
                Mail::to($application->user->email)->send(
                    new InterviewRescheduled($interview)
                );
            } else {
                Mail::to($application->user->email)->send(
                    new InterviewScheduled($interview)
                );
            }
        } catch (\Throwable $e) {
            // Continue if mail driver is offline
        }

        return redirect()
            ->route('admin.applications.show', $application)
            ->with(
                'success',
                $isReschedule
                    ? "{$roundLabel} rescheduled with {$assignedUser->name} and candidate notified."
                    : "{$roundLabel} scheduled with {$assignedUser->name} and candidate notified."
            );
    }

    /**
     * Cancel scheduled interview.
     */
    public function cancel(Request $request, Application $application)
    {
        $type = $request->input('type');
        $interviewQuery = Interview::where('application_id', $application->id)->where('status', 'scheduled');
        if ($type) {
            $interviewQuery->where('type', $type);
        }
        $interview = $interviewQuery->latest()->first() ?? $application->interview;

        if (!$interview) {
            return back()->with(
                'error',
                'No active scheduled interview found to cancel.'
            );
        }

        if ($interview->status !== 'scheduled') {
            return back()->with(
                'error',
                'Only scheduled interviews can be cancelled.'
            );
        }

        $interview->load([
            'application.user',
            'application.job',
            'interviewer',
        ]);

        $interview->update([
            'status' => 'cancelled',
        ]);

        $revertStatus = $interview->type === 'technical' ? 'technical_interview' : 'shortlisted';

        $application->update([
            'status' => $revertStatus,
        ]);

        if ($interview->interviewer) {
            $interview->interviewer->notify(
                new ApplicationStatusNotification(
                    'Interview Cancelled',
                    'The scheduled ' . ($interview->type === 'technical' ? 'Technical' : 'HR') . ' interview for ' . $application->user->name . ' has been cancelled.',
                    'interview'
                )
            );
        }

        $application->user->notify(
            new ApplicationStatusNotification(
                'Interview Cancelled',
                'Your interview for ' . $application->job->title . ' has been cancelled.',
                'interview'
            )
        );

        try {
            Mail::to($application->user->email)->send(
                new InterviewCancelled($interview)
            );
        } catch (\Throwable $e) {
            // Continue if mail driver is offline
        }

        return back()->with(
            'success',
            'Interview cancelled and candidate notified.'
        );
    }

    /**
     * Mark interview as completed, submit feedback, and transition pipeline stage.
     */
    public function complete(Request $request, Application $application)
    {
        $validated = $request->validate([
            'type' => [
                'nullable',
                'in:hr,technical',
            ],
            'result' => [
                'nullable',
                'in:passed,failed',
            ],
            'admin_feedback' => [
                'nullable',
                'string',
                'max:5000',
            ],
            'feedback_attachment' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,png,jpg,jpeg',
                'max:10240',
            ],
        ]);

        $type = $validated['type'] ?? ($application->status === 'technical_interview' ? 'technical' : 'hr');
        $result = $validated['result'] ?? 'passed';

        $interview = Interview::where('application_id', $application->id)
            ->where(function ($q) use ($type) {
                $q->where('type', $type)->orWhere('status', 'scheduled');
            })
            ->latest()
            ->first();

        if (!$interview) {
            return back()->with(
                'error',
                'No interview exists for this application.'
            );
        }

        if ($interview->status !== 'scheduled') {
            return back()->with(
                'error',
                'Only scheduled interviews can be marked as completed.'
            );
        }

        $attachmentPath = $interview->feedback_attachment_path;
        if ($request->hasFile('feedback_attachment')) {
            $file = $request->file('feedback_attachment');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'feedback_' . $application->id . '_' . time() . '.' . $extension;
            $attachmentPath = $file->storeAs('interview_attachments', $fileName, 'public');
        }

        $interview->update([
            'status' => 'completed',
            'result' => $result,
            'admin_feedback' => $validated['admin_feedback'] ?? null,
            'feedback_attachment_path' => $attachmentPath,
            'feedback_submitted_at' => now(),
        ]);

        // Recruitment Pipeline Advancement
        if ($result === 'failed') {
            $application->update(['status' => 'rejected']);

            $application->user->notify(
                new ApplicationStatusNotification(
                    'Application Status Update',
                    'Thank you for interviewing with us for ' . $application->job->title . '. Unfortunately, we are not moving forward at this time.',
                    'rejected'
                )
            );
        } else {
            // Passed Round
            if ($interview->type === 'hr') {
                if ($application->requiresTechnicalInterview()) {
                    $application->update(['status' => 'technical_interview']);
                    $application->user->notify(
                        new ApplicationStatusNotification(
                            'HR Interview Passed',
                            'Congratulations! You passed the HR interview round for ' . $application->job->title . '. You will be contacted for the technical round.',
                            'interview'
                        )
                    );
                } else {
                    $application->update(['status' => 'admin_review']);
                    $application->user->notify(
                        new ApplicationStatusNotification(
                            'HR Interview Passed',
                            'Congratulations! You passed the HR interview round for ' . $application->job->title . '. Your application is currently under final review.',
                            'interview'
                        )
                    );
                }
            } else {
                // Technical Round Passed
                $application->update(['status' => 'admin_review']);
                $application->user->notify(
                    new ApplicationStatusNotification(
                        'Technical Interview Passed',
                        'Congratulations! You passed the technical assessment for ' . $application->job->title . '. Your application is in final review.',
                        'interview'
                    )
                );
            }
        }

        return back()->with(
            'success',
            'Interview marked as completed and result recorded.'
        );
    }

    /**
     * Download interview feedback attachment.
     */
    public function downloadAttachment(Application $application)
    {
        $interview = $application->interview;

        if (!$interview || !$interview->feedback_attachment_path) {
            abort(404, 'Interview feedback attachment not found.');
        }

        if (!Storage::disk('public')->exists($interview->feedback_attachment_path)) {
            abort(404, 'Attachment file missing from storage.');
        }

        return Storage::disk('public')->download($interview->feedback_attachment_path);
    }
}