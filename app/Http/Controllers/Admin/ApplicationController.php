<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

use App\Notifications\ApplicationStatusNotification;

class ApplicationController extends Controller
{
    /**
     * Display all job applications.
     */
    public function index()
    {
        $applications = Application::with([
            'user',
            'job',
            'interview',
            'resume',
            'offer'
        ])
        ->latest()
        ->paginate(10);

        return view(
            'admin.applications.index',
            compact('applications')
        );
    }


    /**
     * Display a single application.
     */
    public function show(Application $application)
    {
        $application->load([
            'user',
            'job',
            'resume',
            'interview',
            'offer'
        ]);

        return view(
            'admin.applications.show',
            compact('application')
        );
    }


    /**
     * Update application status.
     */
    public function updateStatus(
    Request $request,
    Application $application
) {
    $validated = $request->validate([
        'status' => [
            'required',
            'in:pending,shortlisted,interview,selected,rejected',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Update Application Status
    |--------------------------------------------------------------------------
    */

    $application->update([
        'status' => $validated['status'],
    ]);


    /*
    |--------------------------------------------------------------------------
    | Notify Candidate When Selected
    |--------------------------------------------------------------------------
    */

    if ($validated['status'] === 'selected') {

        $application->load([
            'user',
            'job',
        ]);

        $application->user->notify(
            new ApplicationStatusNotification(
                'Application Selected',

                'Congratulations! You have been selected for ' .
                $application->job->title .
                '.',

                'application'
            )
        );
    }


    return back()->with(
        'success',
        'Application status updated successfully.'
    );
}
}