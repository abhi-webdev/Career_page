<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Employee;
use App\Models\Interview;

class HRDashboardController extends Controller
{
    /**
     * Display the HR Management Dashboard.
     */
    public function index()
    {
        $metrics = [
            'totalEmployees' => Employee::count(),
            'upcomingJoinings' => Employee::where('status', 'pending')
                ->where('joining_date', '>=', now()->toDateString())
                ->count(),
            'activeEmployees' => Employee::where('status', 'active')->count(),
            'pendingEmployees' => Employee::where('status', 'pending')->count(),
            'totalApplications' => Application::count(),
            'scheduledInterviews' => Interview::where('status', 'scheduled')->count(),
        ];

        // Upcoming Joinings list sorted by nearest first
        $upcomingJoinings = Employee::with(['user', 'application.job'])
            ->where('status', 'pending')
            ->where('joining_date', '>=', now()->toDateString())
            ->orderBy('joining_date', 'asc')
            ->limit(5)
            ->get();

        // Recent Onboarded Employees
        $recentEmployees = Employee::with(['user', 'application.job'])
            ->latest()
            ->limit(5)
            ->get();

        return view('hr.dashboard', compact('metrics', 'upcomingJoinings', 'recentEmployees'));
    }
}
