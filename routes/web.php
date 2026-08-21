<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\InterviewController;
use App\Http\Controllers\Admin\OfferController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\HR\HRDashboardController;
use App\Http\Controllers\HR\HREmployeeController;
use App\Http\Controllers\HR\HRApplicationController;
use App\Http\Controllers\TR\TRDashboardController;
use App\Http\Controllers\TR\TRApplicationController;
use App\Http\Controllers\TR\TRInterviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('jobs.index');
});

Route::get('/jobs', [JobController::class, 'index'])
    ->name('jobs.index');

Route::get('/jobs/{job}', [JobController::class, 'show'])
    ->name('jobs.show');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Candidate & Shared)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/me', [AuthController::class, 'me'])
        ->name('me');

    // Candidate Job Application
    Route::get('/jobs/{job}/apply', [ApplicationController::class, 'create'])
        ->name('applications.create');

    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'store'])
        ->name('applications.store');

    Route::get('/my-applications', [ApplicationController::class, 'index'])
        ->name('applications.index');

    // Singular Candidate Offer Routes
    Route::get('/offer', [ApplicationController::class, 'showOffer'])
        ->name('offers.current');

    Route::get('/applications/{application}/offer', [ApplicationController::class, 'showOffer'])
        ->name('applications.offer.show');

    // Offer Response Actions
    Route::post('/applications/{application}/offer/accept', [ApplicationController::class, 'acceptOffer'])
        ->name('applications.offer.accept');

    Route::post('/applications/{application}/offer/decline', [ApplicationController::class, 'declineOffer'])
        ->name('applications.offer.decline');

    Route::get('/applications/{application}/offer/download', [ApplicationController::class, 'downloadOffer'])
        ->name('applications.offer.download');

    Route::post('/applications/{application}/offer/upload-signed', [ApplicationController::class, 'uploadSignedOffer'])
        ->name('applications.offer.upload-signed');

    Route::get('/applications/{application}/offer/download-signed', [ApplicationController::class, 'downloadSignedOffer'])
        ->name('applications.offer.download-signed');

    Route::post('/applications/{application}/offer/request-joining-date', [ApplicationController::class, 'requestJoiningDate'])
        ->name('applications.offer.request-joining-date');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/resume', [ProfileController::class, 'uploadResume'])
        ->name('profile.resume.upload');

    Route::delete('/profile/resume/{resume}', [ProfileController::class, 'deleteResume'])
        ->name('profile.resume.delete');

    // Notifications
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])
        ->name('notifications.read');

    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.read-all');
});

/*
|--------------------------------------------------------------------------
| Employee Portal Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:employee,admin,hr,tr'])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| HR Portal Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:hr,admin'])
    ->prefix('hr')
    ->name('hr.')
    ->group(function () {
        Route::get('/dashboard', [HRDashboardController::class, 'index'])
            ->name('dashboard');

        // Employees
        Route::get('/employees', [HREmployeeController::class, 'index'])
            ->name('employees.index');

        Route::get('/employees/{employee}', [HREmployeeController::class, 'show'])
            ->name('employees.show');

        Route::patch('/employees/{employee}/status', [HREmployeeController::class, 'updateStatus'])
            ->name('employees.status');

        Route::get('/employees/{employee}/signed-offer', [HREmployeeController::class, 'downloadSignedOffer'])
            ->name('employees.signed-offer');

        // Candidate Applications & Recruitment review
        Route::get('/applications', [HRApplicationController::class, 'index'])
            ->name('applications.index');

        Route::get('/applications/{application}', [HRApplicationController::class, 'show'])
            ->name('applications.show');

        // HR Interviews
        Route::get('/interviews', [\App\Http\Controllers\HR\HRInterviewController::class, 'index'])
            ->name('interviews.index');

        Route::get('/applications/{application}/interview/create', [\App\Http\Controllers\HR\HRInterviewController::class, 'create'])
            ->name('applications.interview.create');

        Route::post('/applications/{application}/interview', [\App\Http\Controllers\HR\HRInterviewController::class, 'store'])
            ->name('applications.interview.store');

        Route::patch('/applications/{application}/interview/complete', [\App\Http\Controllers\HR\HRInterviewController::class, 'complete'])
            ->name('applications.interview.complete');

        Route::get('/applications/{application}/interview/download-attachment', [\App\Http\Controllers\HR\HRInterviewController::class, 'downloadAttachment'])
            ->name('applications.interview.download-attachment');
    });

/*
|--------------------------------------------------------------------------
| Technical Recruiter (TR) Portal Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:tr,admin'])
    ->prefix('tr')
    ->name('tr.')
    ->group(function () {
        Route::get('/dashboard', [TRDashboardController::class, 'index'])
            ->name('dashboard');

        // Candidate Screening & Pipeline
        Route::get('/applications', [TRApplicationController::class, 'index'])
            ->name('applications.index');

        Route::get('/applications/{application}', [TRApplicationController::class, 'show'])
            ->name('applications.show');

        Route::patch('/applications/{application}/status', [TRApplicationController::class, 'updateStatus'])
            ->name('applications.status');

        // Technical Interviews
        Route::get('/interviews', [TRInterviewController::class, 'index'])
            ->name('interviews.index');

        Route::get('/applications/{application}/interview/create', [TRInterviewController::class, 'create'])
            ->name('applications.interview.create');

        Route::post('/applications/{application}/interview', [TRInterviewController::class, 'store'])
            ->name('applications.interview.store');

        Route::patch('/applications/{application}/interview/complete', [TRInterviewController::class, 'complete'])
            ->name('applications.interview.complete');

        Route::get('/applications/{application}/interview/download-attachment', [TRInterviewController::class, 'downloadAttachment'])
            ->name('applications.interview.download-attachment');
    });

/*
|--------------------------------------------------------------------------
| Admin Portal Routes (Highest Privilege)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Manage Jobs
        Route::get('/jobs', [JobController::class, 'adminIndex'])
            ->name('jobs.index');

        Route::get('/jobs/create', [JobController::class, 'create'])
            ->name('jobs.create');

        Route::post('/jobs', [JobController::class, 'store'])
            ->name('jobs.store');

        Route::get('/jobs/{job}/edit', [JobController::class, 'edit'])
            ->name('jobs.edit');

        Route::put('/jobs/{job}', [JobController::class, 'update'])
            ->name('jobs.update');

        Route::delete('/jobs/{job}', [JobController::class, 'destroy'])
            ->name('jobs.destroy');

        // Admin Applications
        Route::get('/applications', [AdminApplicationController::class, 'index'])
            ->name('applications.index');

        Route::get('/applications/{application}', [AdminApplicationController::class, 'show'])
            ->name('applications.show');

        Route::patch('/applications/{application}/status', [AdminApplicationController::class, 'updateStatus'])
            ->name('applications.status');

        // Interview Management
        Route::get('/applications/{application}/interview/create', [InterviewController::class, 'create'])
            ->name('applications.interview.create');

        Route::post('/applications/{application}/interview', [InterviewController::class, 'store'])
            ->name('applications.interview.store');

        Route::patch('/applications/{application}/interview/cancel', [InterviewController::class, 'cancel'])
            ->name('applications.interview.cancel');

        Route::patch('/applications/{application}/interview/complete', [InterviewController::class, 'complete'])
            ->name('applications.interview.complete');

        Route::get('/applications/{application}/interview/download-attachment', [InterviewController::class, 'downloadAttachment'])
            ->name('applications.interview.download-attachment');

        // Offer Management
        Route::get('/applications/{application}/offer/create', [OfferController::class, 'create'])
            ->name('applications.offer.create');

        Route::post('/applications/{application}/offer', [OfferController::class, 'store'])
            ->name('applications.offer.store');

        Route::post('/applications/{application}/offer/send', [OfferController::class, 'send'])
            ->name('applications.offer.send');

        Route::post('/applications/{application}/offer/generate-letter', [OfferController::class, 'generateLetter'])
            ->name('applications.offer.generate-letter');

        Route::post('/applications/{application}/offer/revise', [OfferController::class, 'revise'])
            ->name('applications.offer.revise');

        Route::get('/applications/{application}/offer/download', [OfferController::class, 'downloadLetter'])
            ->name('applications.offer.download');

        Route::get('/applications/{application}/offer/download-signed', [OfferController::class, 'downloadSigned'])
            ->name('applications.offer.download-signed');

        // Employee Management
        Route::get('/employees', [EmployeeController::class, 'index'])
            ->name('employees.index');

        Route::get('/employees/{employee}', [EmployeeController::class, 'show'])
            ->name('employees.show');

        Route::post('/employees/{employee}/role', [EmployeeController::class, 'updateRole'])
            ->name('employees.role.update');

        Route::patch('/employees/{employee}/status', [EmployeeController::class, 'updateStatus'])
            ->name('employees.status');

        Route::get('/employees/{employee}/signed-offer', [EmployeeController::class, 'downloadSignedOffer'])
            ->name('employees.signed-offer');
    });
