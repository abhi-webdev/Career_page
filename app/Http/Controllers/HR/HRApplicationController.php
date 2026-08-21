<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class HRApplicationController extends Controller
{
    /**
     * Display candidate applications for HR.
     */
    public function index(Request $request)
    {
        $query = Application::with(['user', 'job', 'interview', 'offer', 'employee']);

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
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'shortlisted' => Application::where('status', 'shortlisted')->count(),
            'interview' => Application::where('status', 'interview')->count(),
            'selected' => Application::where('status', 'selected')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        return view('hr.applications.index', compact('applications', 'stageCounts'));
    }

    /**
     * Display a specific candidate application profile for HR.
     */
    public function show(Application $application)
    {
        $application->load([
            'user',
            'job',
            'resume',
            'interview',
            'offer.versions',
            'employee',
        ]);

        return view('hr.applications.show', compact('application'));
    }
}
