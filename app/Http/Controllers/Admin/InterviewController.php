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

    /*


    /**
     * Schedule interview.
     */
   public function store(
    Request $request,
    Application $application
) {



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

    /*
|--------------------------------------------------------------------------
| Application Validation
|--------------------------------------------------------------------------
*/

if ($application->status === 'rejected') {

    return back()->with(
        'error',
        'A rejected application cannot have an interview scheduled.'
    );
}


/*
|--------------------------------------------------------------------------
| Candidate must be shortlisted
|--------------------------------------------------------------------------
*/

if (!in_array($application->status, [
    'shortlisted',
    'interview',
])) {

    return back()->with(
        'error',
        'The candidate must be shortlisted before scheduling an interview.'
    );
}

    /*
    |--------------------------------------------------------------------------
    | Existing Interview
    |--------------------------------------------------------------------------
    */

    $existingInterview = $application->interview;

    if (
    $existingInterview &&
    $existingInterview->status === 'completed'
) {

    return back()->with(
        'error',
        'A completed interview cannot be rescheduled.'
    );
}

    /*
    |--------------------------------------------------------------------------
    | Determine whether this is a reschedule
    |--------------------------------------------------------------------------
    */

    $isReschedule =
        $existingInterview &&
        $existingInterview->status === 'scheduled';


    /*
    |--------------------------------------------------------------------------
    | Create / Update Interview
    |--------------------------------------------------------------------------
    */

    $interview = Interview::updateOrCreate(

        [
            'application_id' => $application->id,
        ],

        [

            'interview_date' =>
                $validated['interview_date'],

            'start_time' =>
                $validated['start_time'],

            'end_time' =>
                $validated['end_time'],

            'meeting_link' =>
                $validated['meeting_link'],

            'notes' =>
                $validated['notes'] ?? null,

            'status' =>
                'scheduled',

        ]

    );


    /*
    |--------------------------------------------------------------------------
    | Update Application
    |--------------------------------------------------------------------------
    */

    $application->update([
        'status' => 'interview',
    ]);

    $application->user->notify(
    new ApplicationStatusNotification(
        'Interview Scheduled',
        'Your interview for ' .
        $application->job->title .
        ' has been scheduled.',
        'interview'
    )
);

    /*
    |--------------------------------------------------------------------------
    | Load Relationships
    |--------------------------------------------------------------------------
    */

    $interview->load([
        'application.user',
        'application.job',
    ]);


    /*
    |--------------------------------------------------------------------------
    | Send Email
    |--------------------------------------------------------------------------
    */

    if ($isReschedule) {

        Mail::to(
            $application->user->email
        )->send(
            new InterviewRescheduled($interview)
        );

    } else {

        Mail::to(
            $application->user->email
        )->send(
            new InterviewScheduled($interview)
        );

    }


    return redirect()
        ->route(
            'admin.applications.show',
            $application
        )
        ->with(
            'success',
            $isReschedule
                ? 'Interview rescheduled and candidate notified.'
                : 'Interview scheduled and candidate notified.'
        );
}


    public function cancel(Application $application)
{
    $interview = $application->interview;

    if (!$interview) {
        return back()->with(
            'error',
            'No interview is scheduled for this application.'
        );
    }


    $interview->load([
        'application.user',
        'application.job',
    ]);


    $interview->update([
        'status' => 'cancelled',
    ]);

    $application->user->notify(
    new ApplicationStatusNotification(
        'Interview Cancelled',
        'Your interview for ' .
        $application->job->title .
        ' has been cancelled. Your application remains shortlisted.',
        'interview'
    )
);



    $application->update([
        'status' => 'shortlisted',
    ]);


    $application->user->notify(
    new \App\Notifications\ApplicationStatusNotification(
        'Application Shortlisted',
        'Your application for ' .
        $application->job->title .
        ' has been shortlisted.',
        'application'
    )

    
);

    Mail::to(
        $application->user->email
    )->send(
        new InterviewCancelled($interview)
    );


    return back()->with(
        'success',
        'Interview cancelled and candidate notified.'
    );
}


public function complete(Application $application)
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


    $interview->update([
        'status' => 'completed',
    ]);

    $application->user->notify(
    new ApplicationStatusNotification(
        'Interview Completed',
        'Your interview for ' .
        $application->job->title .
        ' has been completed.',
        'interview'
    )
);


    return back()->with(
        'success',
        'Interview marked as completed.'
    );
}
}