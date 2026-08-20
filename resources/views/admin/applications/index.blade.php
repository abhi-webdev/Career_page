@extends('layouts.app')

@section('title', 'My Applications')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">


        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div>

            <p class="text-sm font-semibold text-indigo-600">
                Candidate Dashboard
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                My Applications
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Track the progress of all your job applications.
            </p>

        </div>


        {{-- ========================================================= --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div
                class="mt-6 rounded-xl border border-emerald-200
                       bg-emerald-50 px-4 py-3
                       text-sm font-medium text-emerald-700"
            >

                ✓ {{ session('success') }}

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- ERROR MESSAGE --}}
        {{-- ========================================================= --}}

        @if(session('error'))

            <div
                class="mt-6 rounded-xl border border-red-200
                       bg-red-50 px-4 py-3
                       text-sm font-medium text-red-700"
            >

                {{ session('error') }}

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- APPLICATION COUNT --}}
        {{-- ========================================================= --}}

        <div class="mt-8 flex items-center justify-between">

            <div>

                <h2 class="text-lg font-semibold text-slate-900">
                    Your Applications
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $applications->total() }}
                    {{ Str::plural('application', $applications->total()) }}
                </p>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- APPLICATION LIST --}}
        {{-- ========================================================= --}}

        <div class="mt-6 space-y-5">

            @forelse($applications as $application)

                @php

                    $status = strtolower($application->status);

                    $statusClasses = match($status) {

                        'pending' =>
                            'border-amber-200 bg-amber-50 text-amber-700',

                        'shortlisted' =>
                            'border-blue-200 bg-blue-50 text-blue-700',

                        'interview' =>
                            'border-violet-200 bg-violet-50 text-violet-700',

                        'selected' =>
                            'border-emerald-200 bg-emerald-50 text-emerald-700',

                        'rejected' =>
                            'border-red-200 bg-red-50 text-red-700',

                        default =>
                            'border-slate-200 bg-slate-50 text-slate-700',

                    };


                    $progress = match($status) {

                        'pending' => 25,

                        'shortlisted' => 50,

                        'interview' => 75,

                        'selected' => 100,

                        'rejected' => 100,

                        default => 25,

                    };

                @endphp


                {{-- ================================================= --}}
                {{-- APPLICATION CARD --}}
                {{-- ================================================= --}}

                <div
                    class="overflow-hidden rounded-2xl border border-slate-200
                           bg-white shadow-sm transition
                           hover:border-indigo-200 hover:shadow-md"
                >

                    <div class="p-6">


                        {{-- ================================================= --}}
                        {{-- TOP --}}
                        {{-- ================================================= --}}

                        <div
                            class="flex flex-col gap-5
                                   sm:flex-row sm:items-start
                                   sm:justify-between"
                        >

                            <div class="flex gap-4">


                                {{-- Company Icon --}}
                                <div
                                    class="flex h-12 w-12 shrink-0
                                           items-center justify-center
                                           rounded-xl bg-indigo-50
                                           text-lg font-bold text-indigo-600"
                                >
                                    {{ strtoupper(substr($application->job->company, 0, 1)) }}
                                </div>


                                {{-- Job Information --}}
                                <div>

                                    <h3
                                        class="text-lg font-bold text-slate-900"
                                    >
                                        {{ $application->job->title }}
                                    </h3>

                                    <p
                                        class="mt-1 text-sm font-medium
                                               text-slate-600"
                                    >
                                        {{ $application->job->company }}
                                    </p>

                                    <div
                                        class="mt-3 flex flex-wrap gap-x-4
                                               gap-y-2 text-xs text-slate-500"
                                    >

                                        <span>
                                            📍 {{ $application->job->location }}
                                        </span>

                                        @if($application->job->job_type)

                                            <span>
                                                💼 {{ $application->job->job_type }}
                                            </span>

                                        @endif

                                        @if($application->job->experience)

                                            <span>
                                                🎯 {{ $application->job->experience }}
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            </div>


                            {{-- Status --}}
                            <div>

                                <span
                                    class="inline-flex rounded-full border
                                           px-3 py-1.5 text-xs
                                           font-semibold capitalize
                                           {{ $statusClasses }}"
                                >
                                    {{ $application->status }}
                                </span>

                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- APPLICATION DATE --}}
                        {{-- ================================================= --}}

                        <div
                            class="mt-6 flex flex-wrap items-center
                                   justify-between gap-3
                                   border-t border-slate-100 pt-5"
                        >

                            <p class="text-xs text-slate-500">

                                Applied on

                                <span class="font-semibold text-slate-700">

                                    {{ $application->created_at->format('d M Y') }}

                                </span>

                            </p>


                            <a
                                href="{{ route('jobs.show', $application->job) }}"
                                class="text-sm font-semibold text-indigo-600
                                       transition hover:text-indigo-700"
                            >
                                View Job →
                            </a>

                        </div>


                        {{-- ================================================= --}}
                        {{-- APPLICATION PROGRESS --}}
                        {{-- ================================================= --}}

                        <div class="mt-6 border-t border-slate-100 pt-6">

                            <div
                                class="flex items-center justify-between"
                            >

                                <p
                                    class="text-xs font-semibold uppercase
                                           tracking-wide text-slate-400"
                                >
                                    Application Progress
                                </p>

                                <p
                                    class="text-xs font-semibold
                                           text-slate-500"
                                >
                                    {{ $progress }}%
                                </p>

                            </div>


                            {{-- Progress Bar --}}

                            <div
                                class="mt-3 h-2 overflow-hidden rounded-full
                                       bg-slate-100"
                            >

                                <div
                                    class="h-full rounded-full
                                           transition-all
                                           {{ $status === 'rejected'
                                                ? 'bg-red-500'
                                                : 'bg-indigo-600' }}"
                                    style="width: {{ $progress }}%"
                                ></div>

                            </div>


                            {{-- Steps --}}

                            @if($status !== 'rejected')

                                <div
                                    class="mt-5 grid grid-cols-4"
                                >

                                    {{-- Applied --}}

                                    <div class="text-left">

                                        <div
                                            class="flex h-7 w-7
                                                   items-center justify-center
                                                   rounded-full
                                                   bg-indigo-600
                                                   text-xs font-bold
                                                   text-white"
                                        >
                                            ✓
                                        </div>

                                        <p
                                            class="mt-2 text-xs
                                                   font-medium text-slate-600"
                                        >
                                            Applied
                                        </p>

                                    </div>


                                    {{-- Shortlisted --}}

                                    <div class="text-center">

                                        <div
                                            class="mx-auto flex h-7 w-7
                                                   items-center justify-center
                                                   rounded-full
                                                   {{ in_array($status, [
                                                        'shortlisted',
                                                        'interview',
                                                        'selected'
                                                   ])
                                                        ? 'bg-indigo-600 text-white'
                                                        : 'bg-slate-200 text-slate-400' }}"
                                        >
                                            2
                                        </div>

                                        <p
                                            class="mt-2 text-xs
                                                   font-medium
                                                   text-slate-600"
                                        >
                                            Shortlisted
                                        </p>

                                    </div>


                                    {{-- Interview --}}

                                    <div class="text-center">

                                        <div
                                            class="mx-auto flex h-7 w-7
                                                   items-center justify-center
                                                   rounded-full
                                                   {{ in_array($status, [
                                                        'interview',
                                                        'selected'
                                                   ])
                                                        ? 'bg-indigo-600 text-white'
                                                        : 'bg-slate-200 text-slate-400' }}"
                                        >
                                            3
                                        </div>

                                        <p
                                            class="mt-2 text-xs
                                                   font-medium
                                                   text-slate-600"
                                        >
                                            Interview
                                        </p>

                                    </div>


                                    {{-- Selected --}}

                                    <div class="text-right">

                                        <div
                                            class="ml-auto flex h-7 w-7
                                                   items-center justify-center
                                                   rounded-full
                                                   {{ $status === 'selected'
                                                        ? 'bg-emerald-600 text-white'
                                                        : 'bg-slate-200 text-slate-400' }}"
                                        >
                                            4
                                        </div>

                                        <p
                                            class="mt-2 text-xs
                                                   font-medium
                                                   text-slate-600"
                                        >
                                            Selected
                                        </p>

                                    </div>

                                </div>

                            @else

                                {{-- Rejected --}}

                                <div
                                    class="mt-5 rounded-xl border
                                           border-red-200 bg-red-50 p-4"
                                >

                                    <p
                                        class="text-sm font-semibold
                                               text-red-700"
                                    >
                                        Application Rejected
                                    </p>

                                    <p
                                        class="mt-1 text-xs
                                               text-red-600"
                                    >
                                        Unfortunately, your application
                                        was not selected for this position.
                                    </p>

                                </div>

                            @endif

                        </div>

                        {{-- ========================================================= --}}
{{-- INTERVIEW --}}
{{-- ========================================================= --}}

@if($application->interview)

    @php

        $interview = $application->interview;

        $interviewStatus = strtolower($interview->status);

    @endphp


    <div class="border-t border-slate-100 px-6 py-6">

        <div
            class="rounded-2xl border p-5
            {{
                $interviewStatus === 'cancelled'
                    ? 'border-red-200 bg-red-50'
                    : (
                        $interviewStatus === 'completed'
                            ? 'border-emerald-200 bg-emerald-50'
                            : 'border-indigo-200 bg-indigo-50'
                    )
            }}"
        >

            {{-- ================================================= --}}
            {{-- HEADER --}}
            {{-- ================================================= --}}

            <div
                class="flex flex-col gap-4
                       sm:flex-row sm:items-start
                       sm:justify-between"
            >

                <div>

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-slate-500"
                    >
                        Interview
                    </p>

                    <h3
                        class="mt-1 text-lg font-bold text-slate-900"
                    >
                        {{ $application->job->title }}
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Interview for
                        {{ $application->job->company }}
                    </p>

                </div>


                {{-- Interview Status --}}

                <span
                    class="inline-flex w-fit rounded-full border
                           px-3 py-1.5 text-xs font-semibold
                           capitalize

                           {{
                                $interviewStatus === 'scheduled'
                                    ? 'border-blue-200 bg-blue-50 text-blue-700'
                                    : (
                                        $interviewStatus === 'completed'
                                            ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                            : 'border-red-200 bg-red-50 text-red-700'
                                    )
                           }}"
                >
                    {{ $interview->status }}
                </span>

            </div>


            {{-- ================================================= --}}
            {{-- CANCELLED --}}
            {{-- ================================================= --}}

            @if($interviewStatus === 'cancelled')

                <div
                    class="mt-5 rounded-xl border
                           border-red-200 bg-white/70 p-4"
                >

                    <p
                        class="text-sm font-semibold text-red-700"
                    >
                        Interview Cancelled
                    </p>

                    <p
                        class="mt-1 text-xs text-red-600"
                    >
                        This interview has been cancelled.
                        Please wait for further communication.
                    </p>

                </div>


            @else

                {{-- ================================================= --}}
                {{-- DATE & TIME --}}
                {{-- ================================================= --}}

                <div
                    class="mt-5 grid gap-3 sm:grid-cols-3"
                >

                    {{-- Date --}}

                    <div
                        class="rounded-xl bg-white/80 p-4"
                    >

                        <p
                            class="text-xs font-semibold uppercase
                                   tracking-wide text-slate-400"
                        >
                            Date
                        </p>

                        <p
                            class="mt-1 text-sm font-semibold
                                   text-slate-800"
                        >
                            {{ $interview->interview_date
                                ? $interview->interview_date->format('d M Y')
                                : 'Not scheduled' }}
                        </p>

                    </div>


                    {{-- Start Time --}}

                    <div
                        class="rounded-xl bg-white/80 p-4"
                    >

                        <p
                            class="text-xs font-semibold uppercase
                                   tracking-wide text-slate-400"
                        >
                            Start Time
                        </p>

                        <p
                            class="mt-1 text-sm font-semibold
                                   text-slate-800"
                        >

                            @if($interview->start_time)

                                {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }}

                            @else

                                Not scheduled

                            @endif

                        </p>

                    </div>


                    {{-- End Time --}}

                    <div
                        class="rounded-xl bg-white/80 p-4"
                    >

                        <p
                            class="text-xs font-semibold uppercase
                                   tracking-wide text-slate-400"
                        >
                            End Time
                        </p>

                        <p
                            class="mt-1 text-sm font-semibold
                                   text-slate-800"
                        >

                            @if($interview->end_time)

                                {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}

                            @else

                                Not scheduled

                            @endif

                        </p>

                    </div>

                </div>


                {{-- ================================================= --}}
                {{-- MEETING LINK --}}
                {{-- ================================================= --}}

                @if(
                    $interviewStatus === 'scheduled' &&
                    $interview->meeting_link
                )

                    <div class="mt-5">

                        <a
                            href="{{ $interview->meeting_link }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex w-full items-center
                                   justify-center gap-2 rounded-xl
                                   bg-indigo-600 px-5 py-3
                                   text-sm font-semibold text-white
                                   shadow-sm transition
                                   hover:bg-indigo-700
                                   sm:w-auto"
                        >

                            <span>
                                🎥
                            </span>

                            Join Google Meet

                        </a>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- INTERVIEW NOTES --}}
                {{-- ================================================= --}}

                @if($interview->notes)

                    <div
                        class="mt-5 rounded-xl
                               border border-slate-200
                               bg-white/70 p-4"
                    >

                        <p
                            class="text-xs font-semibold uppercase
                                   tracking-wide text-slate-400"
                        >
                            Interview Notes
                        </p>

                        <p
                            class="mt-2 text-sm leading-6
                                   text-slate-600"
                        >
                            {{ $interview->notes }}
                        </p>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- COMPLETED --}}
                {{-- ================================================= --}}

                @if($interviewStatus === 'completed')

                    <div
                        class="mt-5 rounded-xl border
                               border-emerald-200
                               bg-white/70 p-4"
                    >

                        <p
                            class="text-sm font-semibold
                                   text-emerald-700"
                        >
                            ✓ Interview Completed
                        </p>

                        <p
                            class="mt-1 text-xs text-slate-500"
                        >
                            Your interview has been completed.
                        </p>

                    </div>

                @endif

            @endif

        </div>

    </div>

@endif


{{-- ========================================================= --}}
{{-- OFFER --}}
{{-- ========================================================= --}}

@if(
    $application->offer &&
    in_array($application->offer->status, [
        'sent',
        'accepted',
        'declined'
    ])
)

    <div class="border-t border-slate-100 px-6 py-6">

        <div
            class="rounded-2xl border border-emerald-200
                   bg-gradient-to-br from-emerald-50
                   via-white to-indigo-50 p-6"
        >

            {{-- Header --}}

            <div
                class="flex flex-col gap-4
                       sm:flex-row sm:items-start
                       sm:justify-between"
            >

                <div>

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-emerald-600"
                    >
                        Employment Offer
                    </p>

                    <h3
                        class="mt-1 text-xl font-bold text-slate-900"
                    >
                        Congratulations!
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        You have received an offer from
                        {{ $application->job->company }}.
                    </p>

                </div>


                {{-- Status Badge --}}

                @if($application->offer->status === 'sent')

                    <span
                        class="inline-flex w-fit rounded-full
                               border border-blue-200
                               bg-blue-50 px-3 py-1.5
                               text-xs font-semibold
                               text-blue-700"
                    >
                        Awaiting Response
                    </span>

                @elseif($application->offer->status === 'accepted')

                    <span
                        class="inline-flex w-fit rounded-full
                               border border-emerald-200
                               bg-emerald-50 px-3 py-1.5
                               text-xs font-semibold
                               text-emerald-700"
                    >
                        ✓ Accepted
                    </span>

                @elseif($application->offer->status === 'declined')

                    <span
                        class="inline-flex w-fit rounded-full
                               border border-red-200
                               bg-red-50 px-3 py-1.5
                               text-xs font-semibold
                               text-red-700"
                    >
                        Declined
                    </span>

                @endif

            </div>


            {{-- Offer Details --}}

            <div
                class="mt-6 grid gap-4
                       sm:grid-cols-2 lg:grid-cols-3"
            >

                <div class="rounded-xl bg-white p-4">

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-slate-400"
                    >
                        Position
                    </p>

                    <p
                        class="mt-1 text-sm font-semibold
                               text-slate-800"
                    >
                        {{ $application->job->title }}
                    </p>

                </div>


                <div class="rounded-xl bg-white p-4">

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-slate-400"
                    >
                        Salary
                    </p>

                    <p
                        class="mt-1 text-lg font-bold text-slate-900"
                    >
                        ₹{{ number_format($application->offer->salary, 2) }}
                    </p>

                </div>


                <div class="rounded-xl bg-white p-4">

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-slate-400"
                    >
                        Joining Date
                    </p>

                    <p
                        class="mt-1 text-sm font-semibold
                               text-slate-800"
                    >
                        {{ \Carbon\Carbon::parse($application->offer->joining_date)->format('d M Y') }}
                    </p>

                </div>


                @if($application->offer->offer_expiry_date)

                    <div class="rounded-xl bg-white p-4">

                        <p
                            class="text-xs font-semibold uppercase
                                   tracking-wide text-slate-400"
                        >
                            Offer Valid Until
                        </p>

                        <p
                            class="mt-1 text-sm font-semibold
                                   text-slate-800"
                        >
                            {{ \Carbon\Carbon::parse($application->offer->offer_expiry_date)->format('d M Y') }}
                        </p>

                    </div>

                @endif

            </div>


            {{-- Notes --}}

            @if($application->offer->notes)

                <div
                    class="mt-5 rounded-xl
                           border border-slate-100
                           bg-white p-4"
                >

                    <p
                        class="text-xs font-semibold uppercase
                               tracking-wide text-slate-400"
                    >
                        Additional Information
                    </p>

                    <p
                        class="mt-2 text-sm leading-6 text-slate-600"
                    >
                        {{ $application->offer->notes }}
                    </p>

                </div>

            @endif


            {{-- PDF --}}

            @if($application->offer->offer_letter_path)

                <div class="mt-5 flex flex-col gap-3 sm:flex-row">

                    <a
                        href="{{ asset(
                            'storage/' .
                            $application->offer->offer_letter_path
                        ) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex flex-1
                               items-center justify-center
                               rounded-xl bg-indigo-600
                               px-5 py-3 text-sm
                               font-semibold text-white
                               transition hover:bg-indigo-700"
                    >
                        View Offer Letter
                    </a>


                    <a
                        href="{{ asset(
                            'storage/' .
                            $application->offer->offer_letter_path
                        ) }}"
                        download
                        class="inline-flex flex-1
                               items-center justify-center
                               rounded-xl border
                               border-slate-200 bg-white
                               px-5 py-3 text-sm
                               font-semibold text-slate-700
                               transition hover:bg-slate-50"
                    >
                        Download PDF
                    </a>

                </div>

            @endif


            {{-- ================================================= --}}
            {{-- ACCEPT / DECLINE --}}
            {{-- ================================================= --}}

            @if($application->offer->status === 'sent')

                <div
                    class="mt-6 border-t border-slate-200
                           pt-6"
                >

                    <p
                        class="text-sm font-semibold
                               text-slate-900"
                    >
                        Respond to this offer
                    </p>

                    <p
                        class="mt-1 text-xs text-slate-500"
                    >
                        Please review the offer before making
                        your decision.
                    </p>


                    <div
                        class="mt-4 flex flex-col gap-3
                               sm:flex-row"
                    >

                        {{-- Accept --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'applications.offer.accept',
                                $application
                            ) }}"
                            class="flex-1"
                        >

                            @csrf

                            <button
                                type="submit"
                                onclick="return confirm(
                                    'Are you sure you want to accept this offer?'
                                )"
                                class="w-full rounded-xl
                                       bg-emerald-600 px-5 py-3
                                       text-sm font-semibold
                                       text-white transition
                                       hover:bg-emerald-700"
                            >
                                Accept Offer
                            </button>

                        </form>


                        {{-- Decline --}}

                        <form
                            method="POST"
                            action="{{ route(
                                'applications.offer.decline',
                                $application
                            ) }}"
                            class="flex-1"
                        >

                            @csrf

                            <button
                                type="submit"
                                onclick="return confirm(
                                    'Are you sure you want to decline this offer?'
                                )"
                                class="w-full rounded-xl
                                       border border-red-200
                                       bg-white px-5 py-3
                                       text-sm font-semibold
                                       text-red-600 transition
                                       hover:bg-red-50"
                            >
                                Decline Offer
                            </button>

                        </form>

                    </div>

                </div>

            @elseif($application->offer->status === 'accepted')

                <div
                    class="mt-6 rounded-xl
                           border border-emerald-200
                           bg-emerald-50 p-4"
                >

                    <p
                        class="text-sm font-semibold
                               text-emerald-800"
                    >
                        ✓ Offer Accepted
                    </p>

                    <p
                        class="mt-1 text-xs text-emerald-700"
                    >
                        Congratulations! You have accepted
                        this employment offer.
                    </p>

                </div>

            @elseif($application->offer->status === 'declined')

                <div
                    class="mt-6 rounded-xl
                           border border-red-200
                           bg-red-50 p-4"
                >

                    <p
                        class="text-sm font-semibold
                               text-red-800"
                    >
                        Offer Declined
                    </p>

                    <p
                        class="mt-1 text-xs text-red-700"
                    >
                        You have declined this employment offer.
                    </p>

                </div>

            @endif

        </div>

    </div>

@endif

                    </div>

                </div>

            @empty


                {{-- ================================================= --}}
                {{-- EMPTY STATE --}}
                {{-- ================================================= --}}

                <div
                    class="rounded-2xl border border-slate-200
                           bg-white px-6 py-16 text-center shadow-sm"
                >

                    <div
                        class="mx-auto flex h-16 w-16
                               items-center justify-center
                               rounded-2xl bg-indigo-50
                               text-2xl"
                    >
                        💼
                    </div>

                    <h3
                        class="mt-5 text-lg font-semibold text-slate-900"
                    >
                        No applications yet
                    </h3>

                    <p
                        class="mx-auto mt-2 max-w-md text-sm
                               text-slate-500"
                    >
                        You haven't applied for any jobs yet.
                        Explore available opportunities and start
                        your career journey.
                    </p>

                    <a
                        href="{{ route('jobs.index') }}"
                        class="mt-6 inline-flex rounded-xl
                               bg-indigo-600 px-5 py-3
                               text-sm font-semibold text-white
                               shadow-sm transition
                               hover:bg-indigo-700"
                    >
                        Explore Jobs
                    </a>

                </div>

            @endforelse

        </div>


        {{-- ========================================================= --}}
        {{-- PAGINATION --}}
        {{-- ========================================================= --}}

        @if($applications->hasPages())

            <div class="mt-8">

                {{ $applications->links() }}

            </div>

        @endif


    </div>

</div>

@endsection