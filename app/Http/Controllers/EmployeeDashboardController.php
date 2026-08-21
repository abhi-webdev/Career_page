<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeDashboardController extends Controller
{
    /**
     * Display the Employee Staff Portal Dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)
            ->with(['application.job', 'offer.versions'])
            ->first();

        return view('employee.dashboard', compact('user', 'employee'));
    }
}
