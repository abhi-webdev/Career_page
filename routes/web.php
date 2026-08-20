<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;

use App\Http\Controllers\Admin\InterviewController;
use App\Http\Controllers\Admin\OfferController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;

Route::get('/', function () {
    return redirect()->route('login');
});

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
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/me', [AuthController::class, 'me'])
        ->name('me');

});


//  Admin Route
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [
        AdminController::class,
        'dashboard'
    ])->name('admin.dashboard');

});


//  Job route

use App\Http\Controllers\JobController;

Route::get('/jobs', [JobController::class, 'index'])
    ->name('jobs.index');

Route::get('/jobs/{job}', [JobController::class, 'show'])
    ->name('jobs.show');

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/jobs', [
            JobController::class,
            'adminIndex'
        ])->name('admin.jobs.index');


        Route::get('/jobs/create', [
            JobController::class,
            'create'
        ])->name('admin.jobs.create');


        Route::post('/jobs', [
            JobController::class,
            'store'
        ])->name('admin.jobs.store');


        // ==============================
        // ADMIN APPLICATIONS
        // ==============================

        Route::get('/applications', [
            AdminApplicationController::class,
            'index'
        ])->name('admin.applications.index');


        Route::get('/applications/{application}', [
            AdminApplicationController::class,
            'show'
        ])->name('admin.applications.show');


        Route::patch('/applications/{application}/status', [
            AdminApplicationController::class,
            'updateStatus'
        ])->name('admin.applications.status');


        // ==============================
        // JOB EDIT
        // ==============================

        Route::get('/jobs/{job}/edit', [
            JobController::class,
            'edit'
        ])->name('admin.jobs.edit');


        Route::put('/jobs/{job}', [
            JobController::class,
            'update'
        ])->name('admin.jobs.update');


        Route::delete('/jobs/{job}', [
            JobController::class,
            'destroy'
        ])->name('admin.jobs.destroy');

        
    Route::get('/applications/{application}/interview/create', [
    InterviewController::class,
    'create'
    ])->name('admin.applications.interview.create');

    Route::post('/applications/{application}/interview', [
    InterviewController::class,
        'store'
    ])->name('admin.applications.interview.store');

    });

    Route::patch('/applications/{application}/interview/cancel', [
    InterviewController::class,
    'cancel'
])->name('admin.applications.interview.cancel');

Route::patch('/applications/{application}/interview/complete', [
    InterviewController::class,
    'complete'
])->name('admin.applications.interview.complete');


Route::get('/applications/{application}/offer/create', [
    OfferController::class,
    'create'
])->name('admin.applications.offer.create');


Route::post('/applications/{application}/offer', [
    OfferController::class,
    'store'
])->name('admin.applications.offer.store');


Route::post('/applications/{application}/offer/send', [
    OfferController::class,
    'send'
])->name('admin.applications.offer.send');

Route::post('/applications/{application}/offer/generate-letter', [
    OfferController::class,
    'generateLetter'
])->name('admin.applications.offer.generate-letter');

Route::post(
    '/applications/{application}/offer/accept',
    [ApplicationController::class, 'acceptOffer']
)->name('applications.offer.accept');

Route::post(
    '/applications/{application}/offer/decline',
    [ApplicationController::class, 'declineOffer']
)->name('applications.offer.decline');





Route::middleware('auth')->group(function () {

    Route::get('/jobs/{job}/apply', [
        ApplicationController::class,
        'create'
    ])->name('applications.create');

    Route::post('/jobs/{job}/apply', [
        ApplicationController::class,
        'store'
    ])->name('applications.store');

    Route::get('/my-applications', [
        ApplicationController::class,
        'index'
    ])->name('applications.index');

    Route::get('/profile', [
    ProfileController::class,
    'index'
])->name('profile');

Route::put('/profile', [
    ProfileController::class,
    'update'
])->name('profile.update');

Route::post('/profile/resume', [
    ProfileController::class,
    'uploadResume'
])->name('profile.resume.upload');

Route::delete('/profile/resume/{resume}', [
    ProfileController::class,
    'deleteResume'
])->name('profile.resume.delete');


Route::post('/notifications/{notification}/read', [
    NotificationController::class,
    'read'
])->name('notifications.read');

Route::post('/notifications/read-all', [
    NotificationController::class,
    'readAll'
])->name('notifications.read-all');



});

