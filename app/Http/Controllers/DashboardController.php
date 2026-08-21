<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Employee;
use App\Models\Interview;
use App\Models\Offer;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user) {
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'hr') {
                return redirect()->route('hr.dashboard');
            }
            if ($user->role === 'tr') {
                return redirect()->route('tr.dashboard');
            }
            if ($user->role === 'employee') {
                return redirect()->route('employee.dashboard');
            }
        }

        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Applications
        |--------------------------------------------------------------------------
        */

        $totalApplications = Application::where(
            'user_id',
            $userId
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Interviews
        |--------------------------------------------------------------------------
        */

        $totalInterviews = Application::where(
            'user_id',
            $userId
        )
        ->whereHas('interview')
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Offers
        |--------------------------------------------------------------------------
        */

        $totalOffers = Application::where(
            'user_id',
            $userId
        )
        ->whereHas('offer')
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Accepted Offers
        |--------------------------------------------------------------------------
        */

        $acceptedOffers = Application::where(
            'user_id',
            $userId
        )
        ->whereHas('offer', function ($query) {
            $query->where('status', 'accepted');
        })
        ->count();

        /*
        |--------------------------------------------------------------------------
        | Employee Record (If Hired)
        |--------------------------------------------------------------------------
        */

        $employee = Employee::where('user_id', $userId)
            ->with(['application.job', 'offer'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Upcoming Interview
        |--------------------------------------------------------------------------
        */

        $upcomingInterview = Interview::whereHas(
            'application',
            function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }
        )
        ->where('status', 'scheduled')
        ->whereDate('interview_date', '>=', now()->toDateString())
        ->orderBy('interview_date')
        ->orderBy('start_time')
        ->with(['application.job'])
        ->first();

        /*
        |--------------------------------------------------------------------------
        | Latest Application
        |--------------------------------------------------------------------------
        */

        $latestApplication = Application::where('user_id', $userId)
            ->with(['job', 'interview', 'offer', 'employee'])
            ->latest()
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Latest Offer
        |--------------------------------------------------------------------------
        */

        $latestOffer = Offer::whereHas(
            'application',
            function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }
        )
        ->whereIn('status', ['sent', 'accepted', 'declined'])
        ->with(['application.job', 'application.employee'])
        ->latest()
        ->first();

        return view(
            'dashboard',
            compact(
                'totalApplications',
                'totalInterviews',
                'totalOffers',
                'acceptedOffers',
                'employee',
                'upcomingInterview',
                'latestApplication',
                'latestOffer'
            )
        );
    }
}