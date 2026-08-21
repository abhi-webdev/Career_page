<?php

namespace App\Http\Controllers\TR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Interview;

class TRDashboardController extends Controller
{
    /**
     * Display Technical Recruiter Dashboard with scoped interviews, today's schedule, and pipeline metrics.
     */
    public function index()
    {
        $userId = auth()->id();

        // Scoped technical interviews assigned to authenticated TR user
        $baseInterviewQuery = Interview::where('type', 'technical')->where('interviewer_id', $userId);

        $metrics = [
            'totalCandidates' => Application::whereHas('job', fn($q) => $q->where('technical_interview_required', true))->count(),
            'todayInterviews' => (clone $baseInterviewQuery)->whereDate('interview_date', today())->count(),
            'scheduledInterviews' => (clone $baseInterviewQuery)->where('interview_date', '>=', today())->where('status', 'scheduled')->count(),
            'completedInterviews' => (clone $baseInterviewQuery)->where('status', 'completed')->count(),
            'passedInterviews' => (clone $baseInterviewQuery)->where('status', 'completed')->where('result', 'passed')->count(),
        ];

        // Today's technical interviews sorted by start time
        $todayInterviews = (clone $baseInterviewQuery)
            ->whereDate('interview_date', today())
            ->with(['application.user', 'application.job'])
            ->orderBy('start_time', 'asc')
            ->get();

        // Upcoming technical interviews
        $upcomingInterviews = (clone $baseInterviewQuery)
            ->where('interview_date', '>=', today())
            ->where('status', 'scheduled')
            ->with(['application.user', 'application.job'])
            ->orderBy('interview_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();

        // Recent evaluations submitted
        $recentEvaluations = (clone $baseInterviewQuery)
            ->where('status', 'completed')
            ->with(['application.user', 'application.job'])
            ->latest('feedback_submitted_at')
            ->limit(5)
            ->get();

        return view('tr.dashboard', compact(
            'metrics',
            'todayInterviews',
            'upcomingInterviews',
            'recentEvaluations'
        ));
    }
}
