<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HREmployeeController extends Controller
{
    /**
     * Display HR Employee Directory.
     */
    public function index(Request $request)
    {
        $query = Employee::with(['user', 'application.job', 'offer']);

        if ($request->filled('status') && in_array($request->status, ['pending', 'active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('application.job', function ($jq) use ($search) {
                        $jq->where('title', 'like', "%{$search}%")
                            ->orWhere('company', 'like', "%{$search}%");
                    });
            });
        }

        $employees = $query->latest()->paginate(10)->withQueryString();

        $metrics = [
            'total' => Employee::count(),
            'pending' => Employee::where('status', 'pending')->count(),
            'active' => Employee::where('status', 'active')->count(),
            'inactive' => Employee::where('status', 'inactive')->count(),
        ];

        return view('hr.employees.index', compact('employees', 'metrics'));
    }

    /**
     * Display HR Employee Profile (Read-only for roles, but can update onboarding status).
     */
    public function show(Employee $employee)
    {
        $employee->load([
            'user',
            'application.job',
            'application.resume',
            'application.interview',
            'offer.versions',
        ]);

        return view('hr.employees.show', compact('employee'));
    }

    /**
     * HR Update employee onboarding status.
     */
    public function updateStatus(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,active,inactive',
        ]);

        $updateData = ['status' => $validated['status']];

        if ($validated['status'] === 'active' && !$employee->joined_at) {
            $updateData['joined_at'] = now();
        }

        $employee->update($updateData);

        return back()->with('success', "Employee status updated to {$validated['status']}.");
    }

    /**
     * HR Download Signed Offer Document.
     */
    public function downloadSignedOffer(Employee $employee)
    {
        if (!$employee->offer || !$employee->offer->signed_offer_letter_path) {
            return back()->with('error', 'Signed offer letter is not on file for this employee.');
        }

        if (!Storage::disk('public')->exists($employee->offer->signed_offer_letter_path)) {
            return back()->with('error', 'Signed offer letter file not found in server storage.');
        }

        return Storage::disk('public')->download(
            $employee->offer->signed_offer_letter_path,
            'Signed_Offer_' . $employee->employee_code . '_' . str_replace(' ', '_', $employee->user->name) . '.pdf'
        );
    }
}
