<?php

namespace App\Http\Controllers;

use App\Mail\JobApplied;
use App\Mail\OfferAccepted;
use App\Models\Application;
use App\Models\Employee;
use App\Models\Job;
use App\Models\Resume;
use App\Notifications\ApplicationStatusNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        | Check application deadlines
        |--------------------------------------------------------------------------
        */

        if ($job->application_start && now()->lt($job->application_start)) {
            return redirect()
                ->route('jobs.show', $job)
                ->with(
                    'error',
                    'Applications for this job have not opened yet.'
                );
        }

        if ($job->application_deadline && now()->gt($job->application_deadline)) {
            return redirect()
                ->route('jobs.show', $job)
                ->with(
                    'error',
                    'The application deadline for this job has passed.'
                );
        }

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

            $extension = $file->getClientOriginalExtension();
            $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
            $fileName = time() . '_' . $safeBase . '.' . $extension;

            $filePath = $file->storeAs(
                'resumes',
                $fileName,
                'public'
            );

            $resume = Resume::create([
                'user_id' => auth()->id(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
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
                    'resume' => 'Please select an existing resume or upload a new resume.'
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Create Application
        |--------------------------------------------------------------------------
        */

        $application = Application::create([
            'user_id' => auth()->id(),
            'job_id' => $job->id,
            'resume_id' => $resumeId,
            'status' => 'pending',
            'cover_letter' => $validated['cover_letter'] ?? null,
        ]);

        $application->load(['job', 'resume', 'user']);

        /*
        |--------------------------------------------------------------------------
        | Send Confirmation Email & In-App Notifications
        |--------------------------------------------------------------------------
        */

        try {
            Mail::to(auth()->user()->email)->send(new JobApplied($application));
        } catch (\Exception $e) {
            logger()->error('Failed sending job application email: ' . $e->getMessage());
        }

        auth()->user()->notify(
            new ApplicationStatusNotification(
                'Application Received',
                'Your application for ' . $job->title . ' at ' . $job->company . ' has been received.',
                'application'
            )
        );

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new ApplicationStatusNotification(
                    'New Application Received',
                    auth()->user()->name . ' applied for ' . $job->title . '.',
                    'application'
                )
            );
        }

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
            'offer.versions',
            'employee',
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


    /**
     * Singular Candidate Offer Page.
     */
    public function showOffer(Application $application = null)
    {
        if ($application) {
            if ($application->user_id !== auth()->id()) {
                abort(403, 'Unauthorized access to offer.');
            }
        } else {
            $application = Application::where('user_id', auth()->id())
                ->whereHas('offer')
                ->latest()
                ->first();

            if (!$application) {
                return redirect()->route('applications.index')->with('error', 'No active employment offer found.');
            }
        }

        $application->load(['user', 'job', 'resume', 'interview', 'offer.versions']);

        return view('offers.show', compact('application'));
    }

    /**
     * Upload signed offer letter PDF.
     */
    public function uploadSignedOffer(Request $request, Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to offer.');
        }

        if (!$application->offer) {
            return back()->with('error', 'No offer is available for this application.');
        }

        if (!in_array($application->offer->status, ['sent', 'accepted'])) {
            return back()->with('error', 'Signed offer letter can only be uploaded for sent or active offers.');
        }

        $request->validate([
            'signed_offer' => [
                'required',
                'file',
                'mimes:pdf',
                'max:5120',
            ],
        ]);

        $file = $request->file('signed_offer');
        $version = $application->offer->version ?? 1;
        $fileName = 'signed_offer_' . $application->id . '_v' . $version . '_' . time() . '_' . Str::random(8) . '.pdf';

        $path = $file->storeAs('signed_offers', $fileName, 'public');

        $application->offer->update([
            'signed_offer_letter_path' => $path,
            'signed_at' => now(),
        ]);

        $application->offer->snapshotVersion($version);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new ApplicationStatusNotification(
                    'Signed Offer Letter Uploaded',
                    auth()->user()->name . ' uploaded the signed offer letter for ' . $application->job->title . '.',
                    'offer'
                )
            );
        }

        return back()->with('success', 'Signed offer letter uploaded successfully.');
    }

    /**
     * Candidate/Admin download signed offer letter.
     */
    public function downloadSignedOffer(Application $application)
    {
        if ($application->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized to download signed offer.');
        }

        if (!$application->offer || !$application->offer->signed_offer_letter_path) {
            return back()->with('error', 'Signed offer letter is not available.');
        }

        if (!Storage::disk('public')->exists($application->offer->signed_offer_letter_path)) {
            return back()->with('error', 'Signed offer letter file not found on server.');
        }

        return Storage::disk('public')->download(
            $application->offer->signed_offer_letter_path,
            'Signed_Offer_' . str_replace(' ', '_', $application->user->name) . '_v' . ($application->offer->version ?? 1) . '.pdf'
        );
    }

    /**
     * Candidate requests different joining date.
     */
    public function requestJoiningDate(Request $request, Application $application)
    {
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to offer.');
        }

        if (!$application->offer || $application->offer->status !== 'sent') {
            return back()->with('error', 'Joining date requests can only be submitted for active offers.');
        }

        $validated = $request->validate([
            'requested_joining_date' => [
                'required',
                'date',
            ],
            'joining_date_note' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $application->offer->update([
            'joining_date_note' => $validated['joining_date_note'],
            'requested_joining_date' => $validated['requested_joining_date'],
            'joining_date_request_status' => 'pending',
            'joining_date_requested_at' => now(),
        ]);

        $application->offer->snapshotVersion($application->offer->version ?? 1);

        // Notify Admins
        $formattedDate = Carbon::parse($validated['requested_joining_date'])->format('d M Y');
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new ApplicationStatusNotification(
                    'Joining Date Change Request',
                    auth()->user()->name . ' requested a different joining date (' . $formattedDate . ') for ' . $application->job->title . '. Reason: ' . $validated['joining_date_note'],
                    'offer'
                )
            );
        }

        return back()->with('success', 'Your joining date change request has been submitted to the recruitment team.');
    }

    /**
     * Accept Offer.
     */
    public function acceptOffer(Application $application)
    {
        $application->load([
            'user',
            'offer',
            'job',
        ]);

        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!$application->offer) {
            return back()->with('error', 'No offer is available for this application.');
        }

        if ($application->offer->status !== 'sent') {
            return back()->with('error', 'This offer cannot be accepted.');
        }

        if (
            $application->offer->offer_expiry_date &&
            now()->startOfDay()->gt($application->offer->offer_expiry_date)
        ) {
            return back()->with('error', 'This offer has expired.');
        }

        // Business Rule: Validate signed offer letter exists in DB and on disk
        if (
            !$application->offer->signed_offer_letter_path ||
            !Storage::disk('public')->exists($application->offer->signed_offer_letter_path)
        ) {
            return back()->with('error', 'Please upload the signed offer letter before accepting the offer.');
        }

        // 1. Mark Offer as accepted
        $application->offer->update([
            'status' => 'accepted',
        ]);

        $application->offer->snapshotVersion($application->offer->version ?? 1, 'accepted');

        // 2. Automatically and idempotently create Employee record with final version joining date
        $employee = Employee::firstOrCreate(
            ['application_id' => $application->id],
            [
                'user_id' => $application->user_id,
                'offer_id' => $application->offer->id,
                'employee_code' => Employee::generateEmployeeCode(),
                'joining_date' => $application->offer->joining_date,
                'status' => 'pending',
            ]
        );

        // 3. Send email to candidate
        try {
            Mail::to($application->user->email)->send(new OfferAccepted($application->offer, $employee));
        } catch (\Throwable $e) {
            // Continue if mail server is not configured in local environment
        }

        // 4. In-App Notifications
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new ApplicationStatusNotification(
                    'Offer Accepted',
                    $application->user->name . ' has accepted the offer for ' . $application->job->title . '.',
                    'offer'
                )
            );

            $admin->notify(
                new ApplicationStatusNotification(
                    'New Employee Added',
                    $application->user->name . ' has been added to Employees (' . $employee->employee_code . ').',
                    'offer'
                )
            );
        }

        $application->user->notify(
            new ApplicationStatusNotification(
                'Offer Accepted Successfully',
                'Your offer for ' . $application->job->title . ' has been accepted successfully. Welcome to the team!',
                'offer'
            )
        );

        return back()->with('success', 'Congratulations! You have accepted the offer.');
    }

    /**
     * Decline Offer with required reason.
     */
    public function declineOffer(Request $request, Application $application)
    {
        $application->load([
            'user',
            'offer',
            'job',
        ]);

        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        if (!$application->offer) {
            return back()->with('error', 'No offer is available for this application.');
        }

        if ($application->offer->status !== 'sent') {
            return back()->with('error', 'This offer cannot be declined.');
        }

        if (
            $application->offer->offer_expiry_date &&
            now()->startOfDay()->gt($application->offer->offer_expiry_date)
        ) {
            return back()->with('error', 'This offer has already expired.');
        }

        $validated = $request->validate([
            'decline_reason' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        $application->offer->update([
            'status' => 'declined',
            'decline_reason' => $validated['decline_reason'],
            'declined_at' => now(),
        ]);

        $application->offer->snapshotVersion($application->offer->version ?? 1, 'declined');

        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(
                new ApplicationStatusNotification(
                    'Offer Declined',
                    $application->user->name . ' has declined the offer for ' . $application->job->title . '. Reason: ' . $validated['decline_reason'],
                    'offer'
                )
            );
        }

        return back()->with('success', 'You have declined the offer.');
    }

    /**
     * Securely download offer letter PDF.
     */
    public function downloadOffer(Application $application)
    {
        if ($application->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized to download offer.');
        }

        if (!$application->offer || !$application->offer->offer_letter_path) {
            return back()->with('error', 'Offer letter PDF is not available.');
        }

        if (!Storage::disk('public')->exists($application->offer->offer_letter_path)) {
            return back()->with('error', 'Offer letter file not found on server.');
        }

        return Storage::disk('public')->download(
            $application->offer->offer_letter_path,
            'Offer_Letter_' . str_replace(' ', '_', $application->user->name) . '_v' . ($application->offer->version ?? 1) . '.pdf'
        );
    }
}