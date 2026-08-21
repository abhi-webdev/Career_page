<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Employee;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Offer;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // 1. Existing offer statistics
        $offerPending = Offer::where('status', 'sent')->count();
        $offerAccepted = Offer::where('status', 'accepted')->count();
        $offerDeclined = Offer::where('status', 'declined')->count();

        // 2. High-level recruitment metrics
        $totalJobs = Job::count();
        $totalApplications = Application::count();
        $activeCandidates = User::where('role', 'user')->count();
        $totalInterviews = Interview::count();
        $selectedCandidates = Application::where('status', 'selected')->count();
        $totalOffers = Offer::count();

        // 3. Employee & Onboarding metrics
        $totalEmployees = Employee::count();
        $upcomingJoiningsCount = Employee::whereDate('joining_date', '>=', now()->toDateString())->count();
        $activeEmployees = Employee::where('status', 'active')->count();
        $pendingEmployees = Employee::where('status', 'pending')->count();

        // 4. Recruitment Funnel Conversion Metrics
        $funnelApplied = $totalApplications;
        $funnelShortlisted = Application::whereIn('status', ['shortlisted', 'interview', 'selected'])->count();
        $funnelInterview = Application::whereIn('status', ['interview', 'selected'])->count();
        $funnelSelected = $selectedCandidates;
        $funnelOffers = $totalOffers;
        $funnelAccepted = $offerAccepted;

        // 5. Status Breakdown
        $statusCounts = [
            'Pending' => Application::where('status', 'pending')->count(),
            'Shortlisted' => Application::where('status', 'shortlisted')->count(),
            'Interview' => Application::where('status', 'interview')->count(),
            'Selected' => Application::where('status', 'selected')->count(),
            'Rejected' => Application::where('status', 'rejected')->count(),
        ];

        // 6. Recent Activity, Agendas & Upcoming Joinings
        $upcomingInterviews = Interview::with(['application.user', 'application.job'])
            ->where('status', 'scheduled')
            ->whereDate('interview_date', '>=', now()->toDateString())
            ->orderBy('interview_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $upcomingJoinings = Employee::with(['user', 'application.job', 'offer'])
            ->whereDate('joining_date', '>=', now()->toDateString())
            ->orderBy('joining_date', 'asc')
            ->take(6)
            ->get();

        $recentApplications = Application::with(['user', 'job', 'interview', 'offer'])
            ->latest()
            ->take(6)
            ->get();

        $recentOffers = Offer::with(['application.user', 'application.job'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'offerPending',
            'offerAccepted',
            'offerDeclined',
            'totalJobs',
            'totalApplications',
            'activeCandidates',
            'totalInterviews',
            'selectedCandidates',
            'totalOffers',
            'totalEmployees',
            'upcomingJoiningsCount',
            'activeEmployees',
            'pendingEmployees',
            'funnelApplied',
            'funnelShortlisted',
            'funnelInterview',
            'funnelSelected',
            'funnelOffers',
            'funnelAccepted',
            'statusCounts',
            'upcomingInterviews',
            'upcomingJoinings',
            'recentApplications',
            'recentOffers'
        ));
    }
}