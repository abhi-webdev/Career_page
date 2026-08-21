@extends('layouts.admin')

@section('title', 'Candidate Applications (ATS)')
@section('header_title', 'Applications Pipeline')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
            ATS Management
        </span>
        <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
            Candidate Applications
        </h1>
        <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Screen candidate CVs, update recruitment stages, and schedule evaluation interviews.
        </p>
    </div>

    {{-- Filters & Search Card --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-white p-5 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-12">
            
            {{-- Search Bar --}}
            <div class="relative sm:col-span-2 lg:col-span-4">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                    🔍
                </span>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Candidate name, email, or role..."
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2.5 pl-10 pr-4 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>

            {{-- Status Filter --}}
            <div class="lg:col-span-3">
                <select
                    name="status"
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="shortlisted" {{ request('status') === 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                    <option value="interview" {{ request('status') === 'interview' ? 'selected' : '' }}>Interview</option>
                    <option value="selected" {{ request('status') === 'selected' ? 'selected' : '' }}>Selected</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            {{-- Job Filter --}}
            <div class="lg:col-span-3">
                <select
                    name="job_id"
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
                    <option value="">All Job Openings</option>
                    @foreach($jobs as $job)
                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                            {{ $job->title }} ({{ $job->company }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Actions --}}
            <div class="flex gap-2 lg:col-span-2">
                <button
                    type="submit"
                    class="w-full rounded-xl bg-brand-500 px-4 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                >
                    Filter
                </button>
                @if(request()->hasAny(['search', 'status', 'job_id']))
                    <a
                        href="{{ route('admin.applications.index') }}"
                        class="flex items-center justify-center rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-2.5 text-xs font-bold text-[#111111] transition hover:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                        title="Clear filters"
                    >
                        ✕
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Applications Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        <div class="border-b border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-bold text-[#111111] dark:text-white">
                    Applications Feed ({{ $applications->total() }})
                </h2>
                <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Showing {{ $applications->firstItem() ?? 0 }} - {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }}
                </p>
            </div>
        </div>

        @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                        <tr>
                            <th class="px-6 py-3.5">Candidate</th>
                            <th class="px-6 py-3.5">Applied Position</th>
                            <th class="px-6 py-3.5">Resume / CV</th>
                            <th class="px-6 py-3.5">Current Stage</th>
                            <th class="px-6 py-3.5">Applied Date</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5] font-medium dark:divide-[#262626]">
                        @foreach($applications as $application)
                            @php
                                $status = strtolower($application->status);
                                $statusBadge = match($status) {
                                    'pending' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                                    'shortlisted' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                                    'interview' => 'bg-brand-500/10 text-brand-500 border-brand-500/20',
                                    'selected' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                    'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                                    default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                };
                            @endphp
                            <tr class="transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                {{-- Candidate Bio --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-500/10 font-bold text-brand-500 dark:bg-brand-500/20">
                                            {{ strtoupper(substr($application->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.applications.show', $application) }}" class="font-bold text-[#111111] hover:text-brand-500 dark:text-white transition">
                                                {{ $application->user->name }}
                                            </a>
                                            <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                                {{ $application->user->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Job Title --}}
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-[#111111] dark:text-white">
                                        {{ $application->job->title }}
                                    </p>
                                    <p class="text-[11px] text-brand-500 font-semibold">
                                        {{ $application->job->company }}
                                    </p>
                                </td>

                                {{-- Resume --}}
                                <td class="px-6 py-4">
                                    @if($application->resume)
                                        <a
                                            href="{{ asset('storage/' . $application->resume->file_path) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 font-bold text-brand-500 hover:text-brand-600 transition"
                                        >
                                            <span>📄</span>
                                            <span>View Resume</span>
                                        </a>
                                    @else
                                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">
                                            No file
                                        </span>
                                    @endif
                                </td>

                                {{-- Status Pill --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[11px] font-bold capitalize {{ $statusBadge }}">
                                        {{ $application->status }}
                                    </span>
                                </td>

                                {{-- Applied Date --}}
                                <td class="px-6 py-4 text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $application->created_at->format('d M Y') }}
                                </td>

                                {{-- Action Link --}}
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('admin.applications.show', $application) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-[#111111] px-3 py-1.5 text-xs font-bold text-white transition hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
                                    >
                                        <span>Review</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="border-t border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
                {{ $applications->links() }}
            </div>
        @else
            <div class="p-16 text-center">
                <span class="text-4xl">👥</span>
                <h3 class="mt-4 text-sm font-bold text-[#111111] dark:text-white">
                    No applications found
                </h3>
                <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    @if(request()->hasAny(['search', 'status', 'job_id']))
                        No candidates match your current search criteria.
                    @else
                        No candidates have applied yet.
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>

@endsection