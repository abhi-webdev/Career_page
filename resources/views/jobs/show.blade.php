@extends('layouts.app')

@section('title', $job->title . ' at ' . $job->company)

@section('content')

<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Breadcrumb / Back --}}
    <a
        href="{{ route('jobs.index') }}"
        class="inline-flex items-center gap-2 text-xs font-semibold text-[#6B6B6B] transition hover:text-brand-500 dark:text-[#A1A1A1] dark:hover:text-brand-500"
    >
        <span>←</span>
        <span>Back to Open Positions</span>
    </a>

    {{-- Main Grid --}}
    <div class="mt-6 grid gap-8 lg:grid-cols-12">

        {{-- Left Column: Job Details & Requirements --}}
        <div class="lg:col-span-8 space-y-6">

            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414]">
                
                {{-- Header --}}
                <div class="border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                        {{ $job->company }}
                    </span>
                    <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
                        {{ $job->title }}
                    </h1>

                    {{-- Badges --}}
                    <div class="mt-5 flex flex-wrap gap-2.5">
                        @if($job->location)
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-[#E5E5E5] bg-white px-3 py-1.5 text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                                📍 {{ $job->location }}
                            </span>
                        @endif

                        @if($job->job_type)
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-brand-500/30 bg-brand-500/10 px-3 py-1.5 text-xs font-bold text-brand-500">
                                💼 {{ $job->job_type }}
                            </span>
                        @endif

                        @if($job->experience)
                            <span class="inline-flex items-center gap-1.5 rounded-lg border border-[#E5E5E5] bg-white px-3 py-1.5 text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                                🎯 {{ $job->experience }}
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Job Description --}}
                <div class="pt-6">
                    <h2 class="text-base font-bold text-[#111111] dark:text-white">
                        About the Role
                    </h2>
                    <div class="mt-4 whitespace-pre-line text-sm leading-relaxed text-[#6B6B6B] dark:text-[#A1A1A1]">
                        {{ $job->description }}
                    </div>
                </div>

                {{-- Skills Required --}}
                @if($job->skills && count($job->skills))
                    <div class="mt-8 border-t border-[#E5E5E5] pt-6 dark:border-[#262626]">
                        <h2 class="text-base font-bold text-[#111111] dark:text-white">
                            Tech Stack & Required Skills
                        </h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($job->skills as $skill)
                                <span class="rounded-xl border border-[#E5E5E5] bg-white px-3 py-1.5 font-mono text-xs font-semibold text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

        </div>

        {{-- Right Column: Application Sidebar Card --}}
        <div class="lg:col-span-4">
            <div class="sticky top-24 rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                
                <h3 class="text-base font-bold text-[#111111] dark:text-white">
                    Apply for this Role
                </h3>
                <p class="mt-1.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Submit your resume and cover letter directly to the engineering team.
                </p>

                {{-- Apply Action Logic --}}
                @auth
                    @if($application)
                        {{-- Already Applied --}}
                        <div class="mt-6 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-emerald-500 text-sm font-bold text-white">
                                    ✓
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                        Application Submitted
                                    </p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        Status: <span class="capitalize font-bold text-[#111111] dark:text-white">{{ $application->status }}</span>
                                    </p>
                                </div>
                            </div>

                            <a
                                href="{{ route('applications.index') }}"
                                class="mt-4 block w-full rounded-xl bg-white border border-[#E5E5E5] py-2.5 text-center text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                            >
                                View in My Applications →
                            </a>
                        </div>

                    @elseif($job->application_deadline && now()->gt($job->application_deadline))
                        {{-- Deadline Expired --}}
                        <div class="mt-6 rounded-xl border border-amber-500/30 bg-amber-500/10 p-4 text-center">
                            <p class="text-xs font-bold text-amber-600 dark:text-amber-400">Applications Closed</p>
                            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                The deadline for this role passed on {{ $job->application_deadline->format('d M Y') }}.
                            </p>
                        </div>

                    @elseif($job->application_start && now()->lt($job->application_start))
                        {{-- Not Open Yet --}}
                        <div class="mt-6 rounded-xl border border-blue-500/30 bg-blue-500/10 p-4 text-center">
                            <p class="text-xs font-bold text-blue-600 dark:text-blue-400">Opening Soon</p>
                            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                Applications open on {{ $job->application_start->format('d M Y') }}.
                            </p>
                        </div>

                    @else
                        {{-- Apply CTA --}}
                        <a
                            href="{{ route('applications.create', $job) }}"
                            class="mt-6 block w-full rounded-xl bg-brand-500 py-3 text-center text-sm font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                        >
                            Apply Now →
                        </a>
                    @endif

                @else
                    @if($job->application_deadline && now()->gt($job->application_deadline))
                        <div class="mt-6 rounded-xl border border-amber-500/30 bg-amber-500/10 p-4 text-center">
                            <p class="text-xs font-bold text-amber-600 dark:text-amber-400">Applications Closed</p>
                            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                The deadline for this role has passed.
                            </p>
                        </div>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="mt-6 block w-full rounded-xl bg-brand-500 py-3 text-center text-sm font-bold text-white shadow-xs transition hover:bg-brand-600"
                        >
                            Sign In to Apply →
                        </a>
                    @endif
                @endauth

                {{-- Sidebar Meta Specs --}}
                <div class="mt-6 space-y-3.5 border-t border-[#E5E5E5] pt-6 dark:border-[#262626]">
                    
                    @if($job->application_deadline)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Deadline</span>
                            <span class="font-bold text-[#111111] dark:text-white">
                                {{ $job->application_deadline->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    @endif

                    @if($job->job_type)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Employment Type</span>
                            <span class="font-bold text-[#111111] dark:text-white">{{ $job->job_type }}</span>
                        </div>
                    @endif

                    @if($job->location)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Location</span>
                            <span class="font-bold text-[#111111] dark:text-white">{{ $job->location }}</span>
                        </div>
                    @endif

                    @if($job->experience)
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Experience</span>
                            <span class="font-bold text-[#111111] dark:text-white">{{ $job->experience }}</span>
                        </div>
                    @endif

                </div>

            </div>
        </div>

    </div>

</div>

@endsection