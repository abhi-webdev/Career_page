<?php

namespace App\Http\Controllers\TR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;

class TRDashboardController extends Controller
{
    /**
     * Display Technical Recruiter Dashboard.
     */
    public function index()
    {
        $metrics = [
            'totalCandidates' => Application::count(),
            'scheduledInterviews' => Interview::where('status', 'scheduled')->count(),
            'completedInterviews' => Interview::where('status', 'completed')->count(),
            'pendingEvaluations' => Interview::where('status', 'completed')
                ->whereNull('admin_feedback')
                ->count(),
            'selectedCandidates' => Application::where('status', 'selected')->count(),
        ];

        // Upcoming technical interviews
        $upcomingInterviews = Interview::with(['application.user', 'application.job'])
            ->where('status', 'scheduled')
            ->where('interview_date', '>=', now()->toDateString())
            ->orderBy('interview_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // Recent evaluations needing attention or completed
        $recentEvaluations = Interview::with(['application.user', 'application.job'])
            ->where('status', 'completed')
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('tr.dashboard', compact('metrics', 'upcomingInterviews', 'recentEvaluations'));
    }
}
