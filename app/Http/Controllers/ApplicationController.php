<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use App\Models\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function create(Job $job)
    {
        $resumes = Resume::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        return view(
            'applications.create',
            compact('job', 'resumes')
        );
    }


    public function store(Request $request, Job $job)
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate application
        |--------------------------------------------------------------------------
        */

        $alreadyApplied = Application::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'job_id',
            $job->id
        )
        ->exists();

        if ($alreadyApplied) {

            return redirect()
                ->route('jobs.show', $job)
                ->with(
                    'error',
                    'You have already applied for this job.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'resume_id' => [
                'nullable',
                'integer',
            ],

            'resume' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120',
            ],

            'cover_letter' => [
                'nullable',
                'string',
                'max:5000',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Resume
        |--------------------------------------------------------------------------
        */

        $resumeId = null;


        /*
        |--------------------------------------------------------------------------
        | Existing Resume
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['resume_id'])) {

            $resume = Resume::where(
                'id',
                $validated['resume_id']
            )
            ->where(
                'user_id',
                auth()->id()
            )
            ->firstOrFail();

            $resumeId = $resume->id;
        }


        /*
        |--------------------------------------------------------------------------
        | New Resume Upload
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('resume')) {

            $file = $request->file('resume');

            $fileName =
                time() . '_' .
                $file->getClientOriginalName();

            $filePath = $file->storeAs(
                'resumes',
                $fileName,
                'public'
            );


            $resume = Resume::create([

                'user_id' => auth()->id(),

                'file_name' =>
                    $file->getClientOriginalName(),

                'file_path' =>
                    $filePath,

            ]);

            $resumeId = $resume->id;
        }


        /*
        |--------------------------------------------------------------------------
        | Require Resume
        |--------------------------------------------------------------------------
        */

        if (!$resumeId) {

            return back()
                ->withErrors([
                    'resume' =>
                        'Please select an existing resume or upload a new resume.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Create Application
        |--------------------------------------------------------------------------
        */

        Application::create([

            'user_id' => auth()->id(),

            'job_id' => $job->id,

            'resume_id' => $resumeId,

            'status' => 'pending',

            'cover_letter' =>
                $validated['cover_letter'] ?? null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('applications.index')
            ->with(
                'success',
                'Application submitted successfully.'
            );
    }


    public function index()
    {
        $applications = Application::with([
            'job',
            'resume',
            'interview',
            'offer'
        ])
        ->where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->paginate(10);

        return view(
            'applications.index',
            compact('applications')
        );
    }


    public function acceptOffer(Application $application)
{
    $application->load([
        'offer',
        'job',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */

    if ($application->user_id !== auth()->id()) {
        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | Offer must exist
    |--------------------------------------------------------------------------
    */

    if (!$application->offer) {
        return back()->with(
            'error',
            'No offer is available for this application.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Offer must be sent
    |--------------------------------------------------------------------------
    */

    if ($application->offer->status !== 'sent') {
        return back()->with(
            'error',
            'This offer cannot be accepted.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Check offer expiry
    |--------------------------------------------------------------------------
    */

    if (
        $application->offer->offer_expiry_date &&
        now()->startOfDay()->gt(
            $application->offer->offer_expiry_date
        )
    ) {
        return back()->with(
            'error',
            'This offer has expired.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Accept Offer
    |--------------------------------------------------------------------------
    */

    $application->offer->update([
        'status' => 'accepted',
    ]);

    $admins = \App\Models\User::where(
    'role',
    'admin'
)->get();

foreach ($admins as $admin) {

    $admin->notify(
        new \App\Notifications\ApplicationStatusNotification(
            'Offer Accepted',
            $application->user->name .
            ' has accepted the offer for ' .
            $application->job->title .
            '.',
            'offer'
        )
    );

}

    return back()->with(
        'success',
        'Congratulations! You have accepted the offer.'
    );
}

public function declineOffer(Application $application)
{
    $application->load([
        'offer',
        'job',
    ]);

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */

    if ($application->user_id !== auth()->id()) {
        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | Offer must exist
    |--------------------------------------------------------------------------
    */

    if (!$application->offer) {
        return back()->with(
            'error',
            'No offer is available for this application.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Offer must be sent
    |--------------------------------------------------------------------------
    */

    if ($application->offer->status !== 'sent') {
        return back()->with(
            'error',
            'This offer cannot be declined.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Decline Offer
    |--------------------------------------------------------------------------
    */

    $application->offer->update([
        'status' => 'declined',
    ]);

    $admins = \App\Models\User::where(
    'role',
    'admin'
)->get();

foreach ($admins as $admin) {

    $admin->notify(
        new \App\Notifications\ApplicationStatusNotification(
            'Offer Declined',
            $application->user->name .
            ' has declined the offer for ' .
            $application->job->title .
            '.',
            'offer'
        )
    );

}

    return back()->with(
        'success',
        'You have declined the offer.'
    );
}
}