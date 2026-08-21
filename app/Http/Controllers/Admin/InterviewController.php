<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;
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
    public function create(Application $application)
    {
        $application->load([
            'user',
            'job',
            'interview',
        ]);

        return view(
            'admin.interviews.create',
            compact('application')
        );
    }

    /**
     * Schedule or reschedule interview.
     */
    public function store(Request $request, Application $application)
    {
        $validated = $request->validate([
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

        if (!in_array($application->status, ['shortlisted', 'interview'])) {
            return back()->with(
                'error',
                'The candidate must be shortlisted before scheduling an interview.'
            );
        }

        $existingInterview = $application->interview;

        if ($existingInterview && $existingInterview->status === 'completed') {
            return back()->with(
                'error',
                'A completed interview cannot be rescheduled.'
            );
        }

        $isReschedule = $existingInterview && $existingInterview->status === 'scheduled';

        $interview = Interview::updateOrCreate(
            [
                'application_id' => $application->id,
            ],
            [
                'interview_date' => $validated['interview_date'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'meeting_link' => $validated['meeting_link'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'scheduled',
            ]
        );

        $application->update([
            'status' => 'interview',
        ]);

        $application->user->notify(
            new ApplicationStatusNotification(
                $isReschedule ? 'Interview Rescheduled' : 'Interview Scheduled',
                $isReschedule
                    ? 'Your interview for ' . $application->job->title . ' has been rescheduled.'
                    : 'Your interview for ' . $application->job->title . ' has been scheduled.',
                'interview'
            )
        );

        $interview->load([
            'application.user',
            'application.job',
        ]);

        if ($isReschedule) {
            Mail::to($application->user->email)->send(
                new InterviewRescheduled($interview)
            );
        } else {
            Mail::to($application->user->email)->send(
                new InterviewScheduled($interview)
            );
        }

        return redirect()
            ->route('admin.applications.show', $application)
            ->with(
                'success',
                $isReschedule
                    ? 'Interview rescheduled and candidate notified.'
                    : 'Interview scheduled and candidate notified.'
            );
    }

    /**
     * Cancel scheduled interview.
     */
    public function cancel(Application $application)
    {
        $interview = $application->interview;

        if (!$interview) {
            return back()->with(
                'error',
                'No interview is scheduled for this application.'
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
        ]);

        $interview->update([
            'status' => 'cancelled',
        ]);

        $application->update([
            'status' => 'shortlisted',
        ]);

        $application->user->notify(
            new ApplicationStatusNotification(
                'Interview Cancelled',
                'Your interview for ' . $application->job->title . ' has been cancelled. Your application remains shortlisted.',
                'interview'
            )
        );

        Mail::to($application->user->email)->send(
            new InterviewCancelled($interview)
        );

        return back()->with(
            'success',
            'Interview cancelled and candidate notified.'
        );
    }

    /**
     * Mark interview as completed and record Admin feedback notes & optional attachment.
     */
    public function complete(Request $request, Application $application)
    {
        $interview = $application->interview;

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

        $validated = $request->validate([
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

        $attachmentPath = $interview->feedback_attachment_path;
        if ($request->hasFile('feedback_attachment')) {
            $file = $request->file('feedback_attachment');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'feedback_' . $application->id . '_' . time() . '.' . $extension;
            $attachmentPath = $file->storeAs('interview_attachments', $fileName, 'public');
        }

        $interview->update([
            'status' => 'completed',
            'admin_feedback' => $validated['admin_feedback'] ?? null,
            'feedback_attachment_path' => $attachmentPath,
            'feedback_submitted_at' => now(),
        ]);

        $application->user->notify(
            new ApplicationStatusNotification(
                'Interview Completed',
                'Your interview for ' . $application->job->title . ' has been completed.',
                'interview'
            )
        );

        return back()->with(
            'success',
            'Interview marked as completed and feedback recorded.'
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