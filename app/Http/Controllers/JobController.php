<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Public Jobs
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
{
    $query = Job::query();

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if ($request->filled('search')) {

        $search = $request->search;

        $query->where(function ($q) use ($search) {

            $q->where('title', 'like', "%{$search}%")
              ->orWhere('company', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%");

        });
    }


    /*
    |--------------------------------------------------------------------------
    | Job Type Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('job_type')) {

        $query->where(
            'job_type',
            $request->job_type
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Experience Filter
    |--------------------------------------------------------------------------
    */

    if ($request->filled('experience')) {

        $query->where(
            'experience',
            'like',
            "%{$request->experience}%"
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Latest Jobs + Pagination
    |--------------------------------------------------------------------------
    */

    $jobs = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();


    return view(
        'jobs.index',
        compact('jobs')
    );
}
    public function show(Job $job)
{
    $application = null;

    if (auth()->check()) {

        $application = \App\Models\Application::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'job_id',
            $job->id
        )
        ->first();
    }

    return view(
        'jobs.show',
        compact('job', 'application')
    );
}


    /*
    |--------------------------------------------------------------------------
    | Admin Jobs
    |--------------------------------------------------------------------------
    */

    public function adminIndex()
    {
        $jobs = Job::withCount('applications')->latest()->paginate(10);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'company' => ['required', 'string', 'max:255'],

            'description' => ['required', 'string'],

            'skills' => ['nullable', 'string'],

            'location' => ['nullable', 'string', 'max:255'],

            'job_type' => [
                'nullable',
                'string',
                'max:100'
            ],

            'experience' => [
                'nullable',
                'string',
                'max:100'
            ],

            'technical_interview_required' => [
                'nullable',
                'boolean',
            ],

            'apply_url' => [
                'nullable',
                'url',
                'max:500'
            ],

            'application_start' => [
                'nullable',
                'date'
            ],

            'application_deadline' => [
                'nullable',
                'date'
            ],

            'screening_date' => [
                'nullable',
                'date'
            ],

            'interview_start' => [
                'nullable',
                'date'
            ],

            'interview_end' => [
                'nullable',
                'date'
            ],

            'result_date' => [
                'nullable',
                'date'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Convert skills string into array
        |--------------------------------------------------------------------------
        */

        $skills = [];

        if (!empty($validated['skills'])) {
            $skills = array_values(
                array_filter(
                    array_map(
                        'trim',
                        explode(',', $validated['skills'])
                    )
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Job
        |--------------------------------------------------------------------------
        */

        Job::create([
            'title' => $validated['title'],

            'company' => $validated['company'],

            'description' => $validated['description'],

            'skills' => $skills,

            'location' => $validated['location'] ?? null,

            'job_type' => $validated['job_type'] ?? null,

            'experience' => $validated['experience'] ?? null,

            'technical_interview_required' => $request->has('technical_interview_required') ? (bool) $request->technical_interview_required : true,

            'apply_url' => $validated['apply_url'] ?? null,

            'application_start' =>
                $validated['application_start'] ?? null,

            'application_deadline' =>
                $validated['application_deadline'] ?? null,

            'screening_date' =>
                $validated['screening_date'] ?? null,

            'interview_start' =>
                $validated['interview_start'] ?? null,

            'interview_end' =>
                $validated['interview_end'] ?? null,

            'result_date' =>
                $validated['result_date'] ?? null,
        ]);

        return redirect()
            ->route('admin.jobs.index')
            ->with(
                'success',
                'Job created successfully.'
            );
    }

    public function edit(Job $job)
{
    return view('admin.jobs.edit', compact('job'));
}

public function update(Request $request, Job $job)
{
    $validated = $request->validate([
        'title' => ['required', 'string', 'max:255'],

        'company' => ['required', 'string', 'max:255'],

        'description' => ['required', 'string'],

        'skills' => ['nullable', 'string'],

        'location' => ['nullable', 'string', 'max:255'],

        'job_type' => [
            'nullable',
            'string',
            'max:100'
        ],

        'experience' => [
            'nullable',
            'string',
            'max:100'
        ],

        'technical_interview_required' => [
            'nullable',
            'boolean',
        ],

        'apply_url' => [
            'nullable',
            'url',
            'max:500'
        ],

        'application_start' => [
            'nullable',
            'date'
        ],

        'application_deadline' => [
            'nullable',
            'date'
        ],

        'screening_date' => [
            'nullable',
            'date'
        ],

        'interview_start' => [
            'nullable',
            'date'
        ],

        'interview_end' => [
            'nullable',
            'date'
        ],

        'result_date' => [
            'nullable',
            'date'
        ],
    ]);

    /*
    |--------------------------------------------------------------------------
    | Convert skills string to array
    |--------------------------------------------------------------------------
    */

    $skills = [];

    if (!empty($validated['skills'])) {
        $skills = array_values(
            array_filter(
                array_map(
                    'trim',
                    explode(',', $validated['skills'])
                )
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Job
    |--------------------------------------------------------------------------
    */

    $job->update([
        'title' => $validated['title'],

        'company' => $validated['company'],

        'description' => $validated['description'],

        'skills' => $skills,

        'location' => $validated['location'] ?? null,

        'job_type' => $validated['job_type'] ?? null,

        'experience' => $validated['experience'] ?? null,

        'technical_interview_required' => $request->has('technical_interview_required') ? (bool) $request->technical_interview_required : false,

        'apply_url' => $validated['apply_url'] ?? null,

        'application_start' =>
            $validated['application_start'] ?? null,

        'application_deadline' =>
            $validated['application_deadline'] ?? null,

        'screening_date' =>
            $validated['screening_date'] ?? null,

        'interview_start' =>
            $validated['interview_start'] ?? null,

        'interview_end' =>
            $validated['interview_end'] ?? null,

        'result_date' =>
            $validated['result_date'] ?? null,
    ]);

    return redirect()
        ->route('admin.jobs.index')
        ->with('success', 'Job updated successfully.');
}

public function destroy(Job $job)
{
    $job->delete();

    return redirect()
        ->route('admin.jobs.index')
        ->with('success', 'Job deleted successfully.');
}
}