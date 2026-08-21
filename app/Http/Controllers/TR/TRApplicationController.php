<?php

namespace App\Http\Controllers\TR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class TRApplicationController extends Controller
{
    /**
     * Display technical candidate pipeline for TR.
     * Shows candidates for jobs that require technical evaluation.
     */
    public function index(Request $request)
    {
        $query = Application::with(['user', 'job', 'resume', 'hrInterview.interviewer', 'technicalInterview.interviewer'])
            ->whereHas('job', function ($jq) {
                $jq->where('technical_interview_required', true);
            });

        $stage = $request->get('stage', $request->get('status', 'all'));

        if ($stage === 'hr_passed') {
            // Cleared HR round, ready for technical scheduling
            $query->where('status', 'technical_interview')
                  ->whereDoesntHave('technicalInterview');
        } elseif ($stage === 'scheduled') {
            // Technical interview scheduled
            $query->whereHas('technicalInterview', fn($tq) => $tq->where('status', 'scheduled'));
        } elseif ($stage === 'completed') {
            // Technical interview completed
            $query->whereHas('technicalInterview', fn($tq) => $tq->where('status', 'completed'));
        } elseif ($stage === 'admin_review') {
            // Passed technical, under final review
            $query->where('status', 'admin_review');
        } elseif ($stage === 'selected') {
            $query->where('status', 'selected');
        } elseif ($stage === 'rejected') {
            $query->where('status', 'rejected');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('job', function ($jq) use ($search) {
                    $jq->where('title', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                });
            });
        }

        $applications = $query->latest()->paginate(10)->withQueryString();

        $baseTechQuery = Application::whereHas('job', fn($jq) => $jq->where('technical_interview_required', true));

        $stageCounts = [
            'total' => (clone $baseTechQuery)->count(),
            'hr_passed' => (clone $baseTechQuery)->where('status', 'technical_interview')->whereDoesntHave('technicalInterview')->count(),
            'scheduled' => (clone $baseTechQuery)->whereHas('technicalInterview', fn($tq) => $tq->where('status', 'scheduled'))->count(),
            'completed' => (clone $baseTechQuery)->whereHas('technicalInterview', fn($tq) => $tq->where('status', 'completed'))->count(),
            'admin_review' => (clone $baseTechQuery)->where('status', 'admin_review')->count(),
            'selected' => (clone $baseTechQuery)->where('status', 'selected')->count(),
            'rejected' => (clone $baseTechQuery)->where('status', 'rejected')->count(),
        ];

        return view('tr.applications.index', compact('applications', 'stageCounts', 'stage'));
    }

    /**
     * Display technical candidate evaluation profile for TR.
     */
    public function show(Application $application)
    {
        $application->load([
            'user',
            'job',
            'resume',
            'hrInterview.interviewer',
            'technicalInterview.interviewer',
            'interviews.interviewer',
        ]);

        return view('tr.applications.show', compact('application'));
    }
}
