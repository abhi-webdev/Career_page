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
    public function index(Request $request)
    {
        $query = Application::with([
            'user',
            'job',
            'interviews',
            'hrInterview',
            'technicalInterview',
            'resume',
            'offer',
            'employee'
        ]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('job', function ($jq) use ($search) {
                    $jq->where('title', 'like', "%{$search}%")
                       ->orWhere('company', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('job_id')) {
            $query->where('job_id', $request->job_id);
        }

        $applications = $query->latest()->paginate(10)->withQueryString();
        $jobs = \App\Models\Job::orderBy('title')->get(['id', 'title', 'company']);

        return view(
            'admin.applications.index',
            compact('applications', 'jobs')
        );
    }

    /**
     * Display a single application dossier.
     */
    public function show(Application $application)
    {
        $application->load([
            'user',
            'job',
            'resume',
            'interviews.interviewer',
            'hrInterview.interviewer',
            'technicalInterview.interviewer',
            'offer.versions',
            'employee'
        ]);

        return view(
            'admin.applications.show',
            compact('application')
        );
    }

    /**
     * Update application status (Shortlist, Final Selection, Rejection).
     */
    public function updateStatus(
        Request $request,
        Application $application
    ) {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,shortlisted,interview,hr_interview,technical_interview,admin_review,selected,rejected',
            ],
        ]);

        $oldStatus = $application->status;
        $newStatus = $validated['status'];

        if ($oldStatus !== $newStatus) {
            $application->update([
                'status' => $newStatus,
            ]);

            if ($newStatus === 'selected') {
                $application->load([
                    'user',
                    'job',
                ]);

                $application->user->notify(
                    new ApplicationStatusNotification(
                        'Application Selected',
                        'Congratulations! You have been selected for ' .
                        $application->job->title . '.',
                        'application'
                    )
                );
            } elseif ($newStatus === 'rejected') {
                $application->user->notify(
                    new ApplicationStatusNotification(
                        'Application Status Update',
                        'Thank you for your interest in ' . $application->job->title . '. Unfortunately, we are not moving forward at this time.',
                        'rejected'
                    )
                );
            }
        }

        return back()->with(
            'success',
            'Application status updated successfully.'
        );
    }
}