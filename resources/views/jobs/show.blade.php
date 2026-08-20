@extends('layouts.app')

@section('title', $job->title)

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Back --}}

        <a
            href="{{ route('jobs.index') }}"
            class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-indigo-600"
        >
            ← Back to Jobs
        </a>


        {{-- Main Grid --}}

        <div class="mt-8 grid gap-8 lg:grid-cols-3">


            {{-- Job Information --}}

            <div class="lg:col-span-2">

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

                    {{-- Header --}}

                    <div>

                        <p class="text-sm font-semibold text-indigo-600">
                            {{ $job->company }}
                        </p>

                        <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">
                            {{ $job->title }}
                        </h1>

                    </div>


                    {{-- Job Meta --}}

                    <div class="mt-6 flex flex-wrap gap-3">

                        @if($job->location)

                            <span class="rounded-full bg-slate-100 px-3 py-1.5 text-sm font-medium text-slate-600">
                                📍 {{ $job->location }}
                            </span>

                        @endif


                        @if($job->job_type)

                            <span class="rounded-full bg-indigo-50 px-3 py-1.5 text-sm font-medium text-indigo-600">
                                💼 {{ $job->job_type }}
                            </span>

                        @endif


                        @if($job->experience)

                            <span class="rounded-full bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-600">
                                🎯 {{ $job->experience }}
                            </span>

                        @endif

                    </div>


                    <div class="my-8 h-px bg-slate-200"></div>


                    {{-- Description --}}

                    <section>

                        <h2 class="text-xl font-bold text-slate-900">
                            Job Description
                        </h2>

                        <div class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">
                            {{ $job->description }}
                        </div>

                    </section>


                    {{-- Skills --}}

                   @if($job->skills && count($job->skills))

    <section class="mt-10">

        <h2 class="text-xl font-bold text-slate-900">
            Skills
        </h2>

        <div class="mt-4 flex flex-wrap gap-2">

            @foreach($job->skills as $skill)

                <span class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium text-slate-700">
                    {{ $skill }}
                </span>

            @endforeach

        </div>

    </section>

@endif


                </div>

            </div>


            {{-- Right Sidebar --}}

            <div>

                <div class="sticky top-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-slate-900">
                        Apply for this job
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Interested in this position? Submit your application and resume.
                    </p>


                    {{-- Apply Button --}}

                    @auth

    @if($application)

        {{-- Already Applied --}}

        <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                    ✓
                </div>

                <div>

                    <p class="text-sm font-semibold text-emerald-800">
                        Application Submitted
                    </p>

                    <p class="mt-1 text-xs text-emerald-600">
                        You have already applied for this position.
                    </p>

                </div>

            </div>


            <a
                href="{{ route('applications.index') }}"
                class="mt-4 block w-full rounded-xl border border-emerald-200 bg-white px-5 py-3 text-center text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50"
            >
                View My Application
            </a>

        </div>

    @else

        {{-- Apply Now --}}

        <a
            href="{{ route('applications.create', $job) }}"
            class="mt-6 block w-full rounded-xl bg-indigo-600 px-5 py-3 text-center text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700 hover:shadow-md"
        >
            Apply Now
        </a>

    @endif

@else

    {{-- Guest --}}

    <a
        href="{{ route('login') }}"
        class="mt-6 block w-full rounded-xl bg-indigo-600 px-5 py-3 text-center text-sm font-semibold text-white transition hover:bg-indigo-700"
    >
        Login to Apply
    </a>

@endauth


                    {{-- Job Details --}}

                    <div class="mt-6 space-y-4 border-t border-slate-200 pt-6">

                        @if($job->location)

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                    Location
                                </p>

                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    {{ $job->location }}
                                </p>

                            </div>

                        @endif


                        @if($job->job_type)

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                    Job Type
                                </p>

                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    {{ $job->job_type }}
                                </p>

                            </div>

                        @endif


                        @if($job->experience)

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                    Experience
                                </p>

                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    {{ $job->experience }}
                                </p>

                            </div>

                        @endif


                        @if($job->application_deadline)

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                    Application Deadline
                                </p>

                                <p class="mt-1 text-sm font-medium text-slate-700">
                                    {{ $job->application_deadline }}
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection