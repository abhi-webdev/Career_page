@extends('layouts.app')

@section('title', 'My Applications')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8">

        {{-- Header --}}

        <div>

            <p class="text-sm font-semibold text-indigo-600">
                Candidate Dashboard
            </p>

            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                My Applications
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Track all the jobs you have applied for.
            </p>

        </div>


        {{-- Success Message --}}

        @if(session('success'))

            <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                ✓ {{ session('success') }}
            </div>

        @endif


        {{-- Error Message --}}

        @if(session('error'))

            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                {{ session('error') }}
            </div>

        @endif


        {{-- Applications --}}

        <div class="mt-8 space-y-6">

            @forelse($applications as $application)

                @php

                    $status = strtolower($application->status);

                    $statusClasses = match($status) {

                        'pending' =>
                            'bg-amber-50 text-amber-700 border-amber-200',

                        'shortlisted' =>
                            'bg-blue-50 text-blue-700 border-blue-200',

                        'interview' =>
                            'bg-purple-50 text-purple-700 border-purple-200',

                        'selected' =>
                            'bg-emerald-50 text-emerald-700 border-emerald-200',

                        'rejected' =>
                            'bg-red-50 text-red-700 border-red-200',

                        default =>
                            'bg-slate-50 text-slate-700 border-slate-200',

                    };


                    $statusLabel = match($status) {

                        'pending' =>
                            'Application Pending',

                        'shortlisted' =>
                            'Shortlisted',

                        'interview' =>
                            'Interview Scheduled',

                        'selected' =>
                            'Selected',

                        'rejected' =>
                            'Rejected',

                        default =>
                            ucfirst($status),

                    };

                @endphp


                {{-- Application Card --}}

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:shadow-md">

                    {{-- Job Header --}}

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                        <div>

                            <p class="text-sm font-semibold text-indigo-600">
                                {{ $application->job->company }}
                            </p>

                            <h2 class="mt-1 text-xl font-bold text-slate-900">
                                {{ $application->job->title }}
                            </h2>

                            @if($application->job->location)

                                <p class="mt-2 text-sm text-slate-500">
                                    📍 {{ $application->job->location }}
                                </p>

                            @endif

                            <p class="mt-2 text-xs text-slate-400">
                                Applied {{ $application->created_at->diffForHumans() }}
                            </p>

                        </div>


                        {{-- Status Badge --}}

                        <div>

                            <span
                                class="inline-flex rounded-full border px-3 py-1.5 text-xs font-semibold {{ $statusClasses }}"
                            >
                                {{ $statusLabel }}
                            </span>

                        </div>

                    </div>


                    {{-- Application Progress --}}

                    <div class="mt-6 border-t border-slate-100 pt-6">

                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                            Application Progress
                        </p>


                        <div class="mt-5 flex items-center">

                            {{-- Applied --}}

                            <div class="flex flex-col items-center">

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full
                                    {{ in_array($status, ['pending', 'shortlisted', 'interview', 'selected'])
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-slate-200 text-slate-400' }}"
                                >
                                    ✓
                                </div>

                                <span class="mt-2 text-xs font-medium text-slate-500">
                                    Applied
                                </span>

                            </div>


                            {{-- Line --}}

                            <div class="mx-2 h-px flex-1 bg-slate-200"></div>


                            {{-- Shortlisted --}}

                            <div class="flex flex-col items-center">

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full
                                    {{ in_array($status, ['shortlisted', 'interview', 'selected'])
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-slate-200 text-slate-400' }}"
                                >
                                    2
                                </div>

                                <span class="mt-2 text-xs font-medium text-slate-500">
                                    Shortlisted
                                </span>

                            </div>


                            {{-- Line --}}

                            <div class="mx-2 h-px flex-1 bg-slate-200"></div>


                            {{-- Interview --}}

                            <div class="flex flex-col items-center">

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full
                                    {{ in_array($status, ['interview', 'selected'])
                                        ? 'bg-indigo-600 text-white'
                                        : 'bg-slate-200 text-slate-400' }}"
                                >
                                    3
                                </div>

                                <span class="mt-2 text-xs font-medium text-slate-500">
                                    Interview
                                </span>

                            </div>


                            {{-- Line --}}

                            <div class="mx-2 h-px flex-1 bg-slate-200"></div>


                            {{-- Selected --}}

                            <div class="flex flex-col items-center">

                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full
                                    {{ $status === 'selected'
                                        ? 'bg-emerald-600 text-white'
                                        : 'bg-slate-200 text-slate-400' }}"
                                >
                                    4
                                </div>

                                <span class="mt-2 text-xs font-medium text-slate-500">
                                    Selected
                                </span>

                            </div>

                        </div>


                        {{-- Rejected --}}

                        @if($status === 'rejected')

                            <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4">

                                <p class="text-sm font-semibold text-red-700">
                                    Application Rejected
                                </p>

                                <p class="mt-1 text-xs text-red-600">
                                    Unfortunately, your application was not selected for this position.
                                </p>

                            </div>

                        @endif

                    </div>


                    {{-- ========================================================= --}}
{{-- APPLICATION ACTIVITY --}}
{{-- ========================================================= --}}

<div class="mt-6 border-t border-slate-100 pt-6">

    <p
        class="text-xs font-semibold uppercase
               tracking-wide text-slate-400"
    >
        Application Activity
    </p>


    <div class="mt-5 space-y-5">


        {{-- Application Submitted --}}

        <div class="flex gap-4">

            <div
                class="flex h-9 w-9 shrink-0
                       items-center justify-center
                       rounded-full bg-indigo-100
                       text-sm font-bold
                       text-indigo-600"
            >
                ✓
            </div>


            <div>

                <p
                    class="text-sm font-semibold
                           text-slate-800"
                >
                    Application Submitted
                </p>

                <p
                    class="mt-1 text-xs text-slate-500"
                >
                    Your application was submitted successfully.
                </p>

                <p
                    class="mt-1 text-xs text-slate-400"
                >
                    {{ $application->created_at->format('d M Y, h:i A') }}
                </p>

            </div>

        </div>


        {{-- Shortlisted --}}

        @if(
            in_array(
                $application->status,
                [
                    'shortlisted',
                    'interview',
                    'selected'
                ]
            )
        )

            <div class="flex gap-4">

                <div
                    class="flex h-9 w-9 shrink-0
                           items-center justify-center
                           rounded-full bg-indigo-100
                           text-sm font-bold
                           text-indigo-600"
                >
                    ✓
                </div>


                <div>

                    <p
                        class="text-sm font-semibold
                               text-slate-800"
                    >
                        Application Shortlisted
                    </p>

                    <p
                        class="mt-1 text-xs text-slate-500"
                    >
                        Your application has been shortlisted
                        by the recruitment team.
                    </p>

                </div>

            </div>

        @endif


        {{-- Interview Scheduled --}}

        @if($application->interview)

            <div class="flex gap-4">

                <div
                    class="flex h-9 w-9 shrink-0
                           items-center justify-center
                           rounded-full bg-blue-100
                           text-sm font-bold
                           text-blue-600"
                >
                    ✓
                </div>


                <div>

                    <p
                        class="text-sm font-semibold
                               text-slate-800"
                    >
                        Interview Scheduled
                    </p>

                    <p
                        class="mt-1 text-xs text-slate-500"
                    >
                        {{ $application->interview->interview_date->format('d M Y') }}
                        ·
                        {{ \Carbon\Carbon::parse(
                            $application->interview->start_time
                        )->format('h:i A') }}
                    </p>


                    @if($application->interview->status === 'completed')

                        <p
                            class="mt-1 text-xs
                                   font-medium
                                   text-emerald-600"
                        >
                            ✓ Interview Completed
                        </p>

                    @elseif($application->interview->status === 'cancelled')

                        <p
                            class="mt-1 text-xs
                                   font-medium
                                   text-red-600"
                        >
                            Interview Cancelled
                        </p>

                    @endif

                </div>

            </div>

        @endif


        {{-- Selected --}}

        @if($application->status === 'selected')

            <div class="flex gap-4">

                <div
                    class="flex h-9 w-9 shrink-0
                           items-center justify-center
                           rounded-full bg-emerald-100
                           text-sm font-bold
                           text-emerald-600"
                >
                    ✓
                </div>


                <div>

                    <p
                        class="text-sm font-semibold
                               text-slate-800"
                    >
                        Candidate Selected
                    </p>

                    <p
                        class="mt-1 text-xs text-slate-500"
                    >
                        Congratulations! You have been selected
                        for this position.
                    </p>

                </div>

            </div>

        @endif


        {{-- Offer --}}

        @if($application->offer)

            <div class="flex gap-4">

                <div
                    class="flex h-9 w-9 shrink-0
                           items-center justify-center
                           rounded-full
                           @if($application->offer->status === 'accepted')
                               bg-emerald-100 text-emerald-600
                           @elseif($application->offer->status === 'declined')
                               bg-red-100 text-red-600
                           @else
                               bg-violet-100 text-violet-600
                           @endif
                           text-sm font-bold"
                >
                    @if($application->offer->status === 'accepted')
                        ✓
                    @elseif($application->offer->status === 'declined')
                        ×
                    @else
                        ★
                    @endif
                </div>


                <div>

                    <p
                        class="text-sm font-semibold
                               text-slate-800"
                    >
                        @if($application->offer->status === 'accepted')

                            Offer Accepted

                        @elseif($application->offer->status === 'declined')

                            Offer Declined

                        @elseif($application->offer->status === 'sent')

                            Offer Sent

                        @else

                            Offer Created

                        @endif
                    </p>


                    <p
                        class="mt-1 text-xs text-slate-500"
                    >

                        @if($application->offer->status === 'accepted')

                            You accepted the employment offer.

                        @elseif($application->offer->status === 'declined')

                            You declined the employment offer.

                        @elseif($application->offer->status === 'sent')

                            Your employment offer is waiting
                            for your response.

                        @else

                            Your employment offer is being prepared.

                        @endif

                    </p>

                </div>

            </div>

        @endif


        {{-- Rejected --}}

        @if($application->status === 'rejected')

            <div class="flex gap-4">

                <div
                    class="flex h-9 w-9 shrink-0
                           items-center justify-center
                           rounded-full bg-red-100
                           text-sm font-bold
                           text-red-600"
                >
                    ×
                </div>


                <div>

                    <p
                        class="text-sm font-semibold
                               text-red-700"
                    >
                        Application Rejected
                    </p>

                    <p
                        class="mt-1 text-xs text-red-600"
                    >
                        Unfortunately, your application was
                        not selected for this position.
                    </p>

                </div>

            </div>

        @endif

    </div>

</div>


                    {{-- Interview Details --}}

@if($application->interview && $status === 'interview')

    <div class="mt-6 rounded-2xl border border-indigo-100 bg-indigo-50 p-5">

        <div class="flex items-start justify-between gap-4">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">
                    Interview Scheduled
                </p>

                <h3 class="mt-1 text-lg font-bold text-slate-900">
                    Your interview has been scheduled
                </h3>

            </div>

            <span class="rounded-full bg-indigo-600 px-3 py-1 text-xs font-semibold text-white">
                Interview
            </span>

        </div>


        <div class="mt-5 grid gap-4 sm:grid-cols-2">

            {{-- Date --}}

            <div class="rounded-xl bg-white p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Interview Date
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">
                    {{ $application->interview->interview_date->format('d M Y') }}
                </p>

            </div>


            {{-- Time --}}

            <div class="rounded-xl bg-white p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                    Interview Time
                </p>

                <p class="mt-1 text-sm font-semibold text-slate-800">

                    {{ \Carbon\Carbon::parse($application->interview->start_time)->format('h:i A') }}

                    -

                    {{ \Carbon\Carbon::parse($application->interview->end_time)->format('h:i A') }}

                </p>

            </div>

        </div>


        {{-- Meeting Link --}}

        <div class="mt-4 rounded-xl bg-white p-4">

            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                Interview Platform
            </p>

            <p class="mt-1 text-sm font-semibold text-slate-800">
                Google Meet
            </p>


            <a
                href="{{ $application->interview->meeting_link }}"
                target="_blank"
                rel="noopener noreferrer"
                class="mt-4 inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
                Join Google Meet →
            </a>

        </div>


        {{-- Notes --}}

        @if($application->interview->notes)

            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4">

                <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">
                    Interview Notes
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-amber-800">
                    {{ $application->interview->notes }}
                </p>

            </div>

        @endif

    </div>

@endif

<!-- offfer section  -->

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
            class="overflow-hidden rounded-2xl
                   border border-emerald-200
                   bg-gradient-to-br from-emerald-50
                   via-white to-indigo-50"
        >

            {{-- ================================================= --}}
            {{-- OFFER HEADER --}}
            {{-- ================================================= --}}

            <div class="p-6">

                <div
                    class="flex flex-col gap-4
                           sm:flex-row sm:items-start
                           sm:justify-between"
                >

                    <div class="flex items-start gap-4">

                        {{-- Icon --}}

                        <div
                            class="flex h-12 w-12 shrink-0
                                   items-center justify-center
                                   rounded-xl bg-emerald-100
                                   text-xl text-emerald-600"
                        >
                            ✓
                        </div>


                        <div>

                            <p
                                class="text-xs font-semibold
                                       uppercase tracking-wide
                                       text-emerald-600"
                            >
                                Employment Offer
                            </p>

                            <h3
                                class="mt-1 text-xl font-bold
                                       text-slate-900"
                            >
                                Congratulations!
                            </h3>

                            <p
                                class="mt-1 text-sm text-slate-500"
                            >
                                You have received an offer from
                                {{ $application->job->company }}.
                            </p>

                        </div>

                    </div>


                    {{-- Status --}}

                    <span
                        class="inline-flex w-fit rounded-full
                               border border-emerald-200
                               bg-emerald-50 px-3 py-1.5
                               text-xs font-semibold
                               capitalize text-emerald-700"
                    >
                        Offer Sent
                    </span>

                </div>


                {{-- ================================================= --}}
                {{-- OFFER DETAILS --}}
                {{-- ================================================= --}}

                <div
                    class="mt-6 grid gap-4
                           sm:grid-cols-2
                           lg:grid-cols-3"
                >

                    {{-- Position --}}

                    <div
                        class="rounded-xl border border-slate-100
                               bg-white p-4"
                    >

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


                    {{-- Salary --}}

                    <div
                        class="rounded-xl border border-slate-100
                               bg-white p-4"
                    >

                        <p
                            class="text-xs font-semibold uppercase
                                   tracking-wide text-slate-400"
                        >
                            Salary
                        </p>

                        <p
                            class="mt-1 text-lg font-bold
                                   text-slate-900"
                        >
                            ₹{{ number_format($application->offer->salary, 2) }}
                        </p>

                    </div>


                    {{-- Joining Date --}}

                    <div
                        class="rounded-xl border border-slate-100
                               bg-white p-4"
                    >

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


                    {{-- Offer Expiry --}}

                    @if($application->offer->offer_expiry_date)

                        <div
                            class="rounded-xl border border-slate-100
                                   bg-white p-4"
                        >

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


                {{-- ================================================= --}}
                {{-- NOTES --}}
                {{-- ================================================= --}}

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
                            class="mt-2 text-sm leading-6
                                   text-slate-600"
                        >
                            {{ $application->offer->notes }}
                        </p>

                    </div>

                @endif


                {{-- ================================================= --}}
                {{-- PDF ACTION --}}
                {{-- ================================================= --}}

                @if($application->offer->offer_letter_path)

                    <div
                        class="mt-6 flex flex-col gap-3
                               sm:flex-row"
                    >

                        {{-- View PDF --}}

                        <a
                            href="{{ asset('storage/' . $application->offer->offer_letter_path) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex flex-1 items-center
                                   justify-center rounded-xl
                                   bg-indigo-600 px-5 py-3
                                   text-sm font-semibold
                                   text-white shadow-sm
                                   transition hover:bg-indigo-700"
                        >
                            View Offer Letter
                        </a>


                        {{-- Download PDF --}}

                        <a
                            href="{{ asset('storage/' . $application->offer->offer_letter_path) }}"
                            download
                            class="inline-flex flex-1 items-center
                                   justify-center rounded-xl
                                   border border-slate-200
                                   bg-white px-5 py-3
                                   text-sm font-semibold
                                   text-slate-700
                                   transition hover:bg-slate-50"
                        >
                            Download PDF
                        </a>

                    </div>

                    {{-- ========================================================= --}}
{{-- OFFER RESPONSE --}}
{{-- ========================================================= --}}

@if($application->offer->status === 'sent')

    <div class="mt-6 border-t border-slate-100 pt-6">

        <p
            class="text-sm font-semibold text-slate-900"
        >
            Respond to this offer
        </p>

        <p
            class="mt-1 text-xs text-slate-500"
        >
            Please review the offer details before making your decision.
        </p>


        <div
            class="mt-4 flex flex-col gap-3 sm:flex-row"
        >

            {{-- Accept --}}

            <form
                action="{{ route(
                    'applications.offer.accept',
                    $application
                ) }}"
                method="POST"
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
                           text-white shadow-sm
                           transition
                           hover:bg-emerald-700"
                >
                    Accept Offer
                </button>

            </form>


            {{-- Decline --}}

            <form
                action="{{ route(
                    'applications.offer.decline',
                    $application
                ) }}"
                method="POST"
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
                           text-red-600
                           transition
                           hover:bg-red-50"
                >
                    Decline Offer
                </button>

            </form>

        </div>

    </div>

@endif

@if($application->offer->status === 'accepted')

    <div
        class="mt-6 rounded-xl border
               border-emerald-200
               bg-emerald-50 p-4"
    >

        <div class="flex items-start gap-3">

            <div
                class="flex h-8 w-8 shrink-0
                       items-center justify-center
                       rounded-full bg-emerald-600
                       text-sm font-bold text-white"
            >
                ✓
            </div>

            <div>

                <p
                    class="text-sm font-semibold
                           text-emerald-800"
                >
                    Offer Accepted
                </p>

                <p
                    class="mt-1 text-xs
                           text-emerald-700"
                >
                    Congratulations! You have accepted
                    this employment offer.
                </p>

            </div>

        </div>

    </div>

@endif


@if($application->offer->status === 'declined')

    <div
        class="mt-6 rounded-xl border
               border-red-200
               bg-red-50 p-4"
    >

        <div class="flex items-start gap-3">

            <div
                class="flex h-8 w-8 shrink-0
                       items-center justify-center
                       rounded-full bg-red-600
                       text-sm font-bold text-white"
            >
                ×
            </div>

            <div>

                <p
                    class="text-sm font-semibold
                           text-red-800"
                >
                    Offer Declined
                </p>

                <p
                    class="mt-1 text-xs
                           text-red-700"
                >
                    You have declined this employment offer.
                </p>

            </div>

        </div>

    </div>

@endif


                @else

                    <div
                        class="mt-5 rounded-xl
                               border border-amber-200
                               bg-amber-50 p-4"
                    >

                        <p
                            class="text-sm font-semibold
                                   text-amber-700"
                        >
                            Offer letter is being prepared
                        </p>

                        <p
                            class="mt-1 text-xs text-amber-600"
                        >
                            Your offer has been sent, but the
                            downloadable offer letter is not
                            available yet.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endif



                    {{-- Resume --}}

                    @if($application->resume)

                        <div class="mt-6 flex items-center justify-between rounded-xl bg-slate-50 p-4">

                            <div>

                                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                    Resume
                                </p>

                                <p class="mt-1 text-sm font-semibold text-slate-700">
                                    {{ $application->resume->file_name }}
                                </p>

                            </div>

                            <a
                                href="{{ asset('storage/' . $application->resume->file_path) }}"
                                target="_blank"
                                class="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                            >
                                View Resume →
                            </a>

                        </div>

                    @endif


                    {{-- Cover Letter --}}

                    @if($application->cover_letter)

                        <div class="mt-6">

                            <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                                Cover Letter
                            </p>

                            <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">
                                {{ $application->cover_letter }}
                            </p>

                        </div>

                    @endif


                    {{-- Footer --}}

                    <div class="mt-6 border-t border-slate-100 pt-5">

                        <a
                            href="{{ route('jobs.show', $application->job) }}"
                            class="text-sm font-semibold text-indigo-600 hover:text-indigo-700"
                        >
                            View Job →
                        </a>

                    </div>

                </div>

            @empty

                {{-- No Applications --}}

                <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-2xl">
                        📄
                    </div>

                    <h2 class="mt-5 text-xl font-bold text-slate-900">
                        No applications yet
                    </h2>

                    <p class="mt-2 text-sm text-slate-500">
                        You haven't applied for any jobs yet.
                    </p>

                    <a
                        href="{{ route('jobs.index') }}"
                        class="mt-6 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-indigo-700"
                    >
                        Browse Jobs
                    </a>

                </div>

            @endforelse

        </div>


        {{-- Pagination --}}

        @if($applications->hasPages())

            <div class="mt-8">

                {{ $applications->links() }}

            </div>

        @endif

    </div>

</div>

@endsection