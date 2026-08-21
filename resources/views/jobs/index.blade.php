@extends('layouts.app')

@section('title', 'Explore Jobs')

@section('content')

<div class="w-full">

    {{-- Hero Section --}}
    <div class="border-b border-[#E5E5E5] bg-[#F7F7F7] py-14 transition-colors duration-200 dark:border-[#262626] dark:bg-[#141414]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center sm:text-left">
            <span class="inline-flex items-center gap-2 rounded-full border border-brand-500/30 bg-brand-500/10 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-brand-500">
                <span>⚡ Open Roles</span>
            </span>
            <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-[#111111] sm:text-5xl dark:text-white">
                Find Your Next Engineering Opportunity
            </h1>
            <p class="mt-3 max-w-2xl text-base text-[#6B6B6B] dark:text-[#A1A1A1]">
                Curated developer roles across high-growth engineering teams. Apply with your verified profile and track your recruitment timeline in real-time.
            </p>
        </div>
    </div>

    {{-- Main Job Search & Feed Container --}}
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Filter & Search Form --}}
        <div class="mb-10 rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414]">
            <form method="GET" action="{{ route('jobs.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-12">
                
                {{-- Search Bar --}}
                <div class="relative sm:col-span-2 lg:col-span-5">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        🔍
                    </span>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by role, company, or technology..."
                        class="w-full rounded-xl border border-[#E5E5E5] bg-white py-3 pl-10 pr-4 text-sm text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Job Type Filter --}}
                <div class="lg:col-span-3">
                    <select
                        name="job_type"
                        class="w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-3 text-sm text-[#111111] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                        <option value="">All Job Types</option>
                        <option value="Full Time" {{ request('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                        <option value="Part Time" {{ request('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                        <option value="Internship" {{ request('job_type') === 'Internship' ? 'selected' : '' }}>Internship</option>
                        <option value="Contract" {{ request('job_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                </div>

                {{-- Location / Keyword Filter --}}
                <div class="lg:col-span-2">
                    <input
                        type="text"
                        name="location"
                        value="{{ request('location') }}"
                        placeholder="Location / Remote"
                        class="w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-3 text-sm text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Actions --}}
                <div class="flex gap-2 lg:col-span-2">
                    <button
                        type="submit"
                        class="w-full rounded-xl bg-brand-500 px-4 py-3 text-sm font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                    >
                        Search
                    </button>
                    @if(request()->hasAny(['search', 'job_type', 'location']))
                        <a
                            href="{{ route('jobs.index') }}"
                            class="flex items-center justify-center rounded-xl border border-[#E5E5E5] bg-white px-3 py-3 text-sm font-bold text-[#111111] transition hover:bg-[#F7F7F7] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                            title="Clear search filters"
                        >
                            ✕
                        </a>
                    @endif
                </div>

            </form>
        </div>

        {{-- Results Counter & Sorting Info --}}
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-sm font-bold tracking-tight text-[#111111] dark:text-white">
                Available Positions ({{ $jobs->total() }})
            </h2>
            <p class="text-xs font-medium text-[#6B6B6B] dark:text-[#A1A1A1]">
                Page {{ $jobs->currentPage() }} of {{ $jobs->lastPage() }}
            </p>
        </div>

        {{-- Jobs Grid --}}
        @if($jobs->count() > 0)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($jobs as $job)
                    @include('components.job-card', ['job' => $job])
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12">
                {{ $jobs->links() }}
            </div>
        @else
            {{-- Polished Developer Empty State --}}
            <div class="rounded-2xl border border-dashed border-[#E5E5E5] bg-[#F7F7F7] p-16 text-center dark:border-[#262626] dark:bg-[#141414]">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-3xl text-brand-500 dark:bg-brand-500/20">
                    🔍
                </span>
                <h3 class="mt-4 text-base font-bold text-[#111111] dark:text-white">
                    No matching positions found
                </h3>
                <p class="mt-1.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1] max-w-sm mx-auto">
                    Try adjusting your keywords or clearing active filters to browse all open opportunities.
                </p>
                @if(request()->hasAny(['search', 'job_type', 'location']))
                    <a
                        href="{{ route('jobs.index') }}"
                        class="mt-5 inline-flex items-center gap-1 rounded-xl bg-brand-500 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-600"
                    >
                        Reset All Filters
                    </a>
                @endif
            </div>
        @endif

    </div>

</div>

@endsection