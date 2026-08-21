<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Employee;
use App\Models\Interview;

class HRDashboardController extends Controller
{
    /**
     * Display the HR Management Dashboard with scoped assigned interviews and employee stats.
     */
    public function index()
    {
        $userId = auth()->id();

        // Scoped interviews assigned to authenticated HR user
        $baseInterviewQuery = Interview::where('type', 'hr')->where('interviewer_id', $userId);

        $metrics = [
            'totalAssigned' => (clone $baseInterviewQuery)->count(),
            'todayInterviews' => (clone $baseInterviewQuery)->whereDate('interview_date', today())->count(),
            'upcomingInterviews' => (clone $baseInterviewQuery)->where('interview_date', '>=', today())->where('status', 'scheduled')->count(),
            'completedInterviews' => (clone $baseInterviewQuery)->where('status', 'completed')->count(),
            'totalEmployees' => Employee::count(),
            'pendingJoinings' => Employee::where('status', 'pending')
                ->where('joining_date', '>=', now()->toDateString())
                ->count(),
        ];

        // Today's interviews sorted by start time
        $todayInterviews = (clone $baseInterviewQuery)
            ->whereDate('interview_date', today())
            ->with(['application.user', 'application.job'])
            ->orderBy('start_time', 'asc')
            ->get();

        // Upcoming interviews (future dates or scheduled)
        $upcomingInterviews = (clone $baseInterviewQuery)
            ->where('interview_date', '>=', today())
            ->where('status', 'scheduled')
            ->with(['application.user', 'application.job'])
            ->orderBy('interview_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // Recently completed interviews
        $completedInterviews = (clone $baseInterviewQuery)
            ->where('status', 'completed')
            ->with(['application.user', 'application.job'])
            ->latest('feedback_submitted_at')
            ->limit(5)
            ->get();

        return view('hr.dashboard', compact(
            'metrics',
            'todayInterviews',
            'upcomingInterviews',
            'completedInterviews'
        ));
    }
}
