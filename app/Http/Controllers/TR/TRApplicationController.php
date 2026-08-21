<?php

namespace App\Http\Controllers\TR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class TRApplicationController extends Controller
{
    /**
     * Display technical candidate pipeline for TR.
     * Shows candidates for jobs that require technical evaluation who cleared HR.
     */
    public function index(Request $request)
    {
        $query = Application::with(['user', 'job', 'resume', 'hrInterview', 'technicalInterview'])
            ->whereHas('job', function ($jq) {
                $jq->where('technical_interview_required', true);
            })
            ->where(function ($q) {
                $q->whereIn('status', ['technical_interview', 'admin_review', 'selected'])
                  ->orWhereHas('technicalInterview');
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $stageCounts = [
            'total' => (clone $query)->count(),
            'pending_eval' => Application::whereHas('job', fn($jq) => $jq->where('technical_interview_required', true))->where('status', 'technical_interview')->count(),
            'admin_review' => Application::whereHas('job', fn($jq) => $jq->where('technical_interview_required', true))->where('status', 'admin_review')->count(),
            'selected' => Application::whereHas('job', fn($jq) => $jq->where('technical_interview_required', true))->where('status', 'selected')->count(),
        ];

        return view('tr.applications.index', compact('applications', 'stageCounts'));
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
            'hrInterview',
            'technicalInterview',
            'interviews.interviewer',
        ]);

        return view('tr.applications.show', compact('application'));
    }
}
