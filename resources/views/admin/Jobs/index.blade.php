@extends('layouts.admin')

@section('title', 'Manage Jobs')
@section('header_title', 'Job Openings & Roles')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                Positions Management
            </span>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                Job Openings
            </h1>
            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Manage all published career opportunities, deadlines, and candidate applicants.
            </p>
        </div>

        <div>
            <a
                href="{{ route('admin.jobs.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
            >
                <span>+</span>
                <span>Post New Job</span>
            </a>
        </div>
    </div>

    {{-- Jobs Table Container --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        <div class="border-b border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                    All Jobs ({{ $jobs->total() }})
                </h2>
                <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Page {{ $jobs->currentPage() }} of {{ $jobs->lastPage() }}
                </p>
            </div>
        </div>

        @if($jobs->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                        <tr>
                            <th class="px-6 py-3.5">Job Title & Company</th>
                            <th class="px-6 py-3.5">Location & Type</th>
                            <th class="px-6 py-3.5">Experience</th>
                            <th class="px-6 py-3.5">Applicants</th>
                            <th class="px-6 py-3.5">Created</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5] font-medium dark:divide-[#262626]">
                        @foreach($jobs as $job)
                            <tr class="transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                {{-- Job Title --}}
                                <td class="px-6 py-4">
                                    <a href="{{ route('jobs.show', $job) }}" target="_blank" class="font-bold text-[#111111] hover:text-brand-500 dark:text-white transition">
                                        {{ $job->title }}
                                    </a>
                                    <p class="text-[11px] text-brand-500 font-semibold mt-0.5">
                                        {{ $job->company }}
                                    </p>
                                </td>

                                {{-- Location & Type --}}
                                <td class="px-6 py-4">
                                    <p class="text-[#111111] dark:text-white">
                                        📍 {{ $job->location ?? 'Remote' }}
                                    </p>
                                    @if($job->job_type)
                                        <span class="inline-flex rounded-md bg-[#F7F7F7] px-2 py-0.5 text-[10px] font-bold text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1] mt-1 border border-[#E5E5E5] dark:border-[#262626]">
                                            {{ $job->job_type }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Experience --}}
                                <td class="px-6 py-4 text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $job->experience ?? 'Any' }}
                                </td>

                                {{-- Applications Count --}}
                                <td class="px-6 py-4">
                                    <a
                                        href="{{ route('admin.applications.index', ['job_id' => $job->id]) }}"
                                        class="inline-flex items-center gap-1 rounded-full border border-brand-500/30 bg-brand-500/10 px-3 py-1 text-xs font-bold text-brand-500 transition hover:bg-brand-500 hover:text-white"
                                        title="View applicants for this role"
                                    >
                                        <span>👥</span>
                                        <span>{{ $job->applications_count ?? $job->applications()->count() }}</span>
                                    </a>
                                </td>

                                {{-- Created Date --}}
                                <td class="px-6 py-4 text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $job->created_at->format('d M Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            href="{{ route('admin.jobs.edit', $job) }}"
                                            class="rounded-lg border border-[#E5E5E5] px-2.5 py-1.5 text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:text-white"
                                        >
                                            Edit
                                        </a>
                                        <form
                                            action="{{ route('admin.jobs.destroy', $job) }}"
                                            method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this job posting?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-500/30 bg-red-500/10 px-2.5 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-500/20 dark:text-red-400"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="p-16 text-center">
                <span class="text-4xl">📄</span>
                <h3 class="mt-4 text-sm font-bold text-[#111111] dark:text-white">
                    No jobs created yet
                </h3>
                <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Get started by publishing your first job opportunity.
                </p>
                <a
                    href="{{ route('admin.jobs.create') }}"
                    class="mt-4 inline-flex items-center gap-1 rounded-xl bg-brand-500 px-4 py-2 text-xs font-bold text-white transition hover:bg-brand-600"
                >
                    + Create Job
                </a>
            </div>
        @endif
    </div>

</div>

@endsection