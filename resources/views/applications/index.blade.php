@extends('layouts.app')

@section('title', 'My Applications')

@section('content')

<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">

    {{-- Page Header --}}
    <div class="border-b border-[#E5E5E5] pb-8 dark:border-[#262626]">
        <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
            Recruitment Tracker
        </span>
        <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
            My Applications
        </h1>
        <p class="mt-1 text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
            Track your progress across engineering hiring pipelines, interview appointments, and employment offer decisions.
        </p>
    </div>

    {{-- Applications List --}}
    @if($applications->count() > 0)
        <div class="space-y-8">
            @foreach($applications as $application)
                @php
                    $status = strtolower($application->status);
                    $employee = $application->employee;
                    $isHired = $employee && ($application->offer && $application->offer->status === 'accepted');
                    
                    $currentStepIndex = $isHired ? 4 : match($status) {
                        'pending' => 0,
                        'shortlisted' => 1,
                        'interview' => 2,
                        'selected' => 3,
                        'rejected' => -1,
                        default => 0,
                    };
                @endphp

                <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 sm:p-8 transition dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    
                    {{-- Application Top Row: Title, Company, Applied Date, Status Badge --}}
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-brand-500 uppercase tracking-wide">
                                    {{ $application->job->company }}
                                </span>
                                @if($isHired)
                                    <span class="font-mono text-xs font-bold text-emerald-600 bg-emerald-500/10 px-2 py-0.5 rounded-lg border border-emerald-500/20">
                                        {{ $employee->employee_code }}
                                    </span>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold tracking-tight text-[#111111] dark:text-white mt-1">
                                <a href="{{ route('jobs.show', $application->job) }}" class="hover:text-brand-500 transition">
                                    {{ $application->job->title }}
                                </a>
                            </h2>
                            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                Applied on {{ $application->created_at->format('d M Y') }} • {{ $application->job->location ?? 'Remote' }}
                            </p>
                        </div>

                        {{-- Overall Candidate Status Pill --}}
                        <div>
                            @if($isHired)
                                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-500/30 bg-emerald-500/10 px-4 py-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                    <span>✓ Hired</span>
                                    <span>•</span>
                                    <span class="font-mono">{{ $employee->employee_code }}</span>
                                </span>
                            @else
                                @php
                                    $badgeStyle = match($status) {
                                        'pending' => 'border-amber-500/30 bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                        'shortlisted' => 'border-blue-500/30 bg-blue-500/10 text-blue-600 dark:text-blue-400',
                                        'interview' => 'border-brand-500/30 bg-brand-500/10 text-brand-500',
                                        'selected' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                        'rejected' => 'border-red-500/30 bg-red-500/10 text-red-600 dark:text-red-400',
                                        default => 'border-slate-500/30 bg-slate-500/10 text-slate-600',
                                    };
                                @endphp
                                <span class="inline-flex rounded-full border px-3.5 py-1 text-xs font-bold capitalize {{ $badgeStyle }}">
                                    Stage: {{ $application->status }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Pipeline Visual Stepper (5 Steps: Applied -> Shortlisted -> Interview -> Selected -> Hired) --}}
                    @if($status !== 'rejected')
                        <div class="py-6 border-b border-[#E5E5E5] dark:border-[#262626]">
                            <p class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1] mb-4">
                                Hiring Pipeline Progress
                            </p>
                            <div class="grid grid-cols-5 gap-2 sm:gap-4 text-center">
                                @php
                                    $stepLabels = [
                                        0 => 'Applied',
                                        1 => 'Shortlisted',
                                        2 => 'Interview',
                                        3 => 'Selected',
                                        4 => 'Hired',
                                    ];
                                @endphp
                                @foreach($stepLabels as $idx => $label)
                                    @php
                                        $isPassed = $currentStepIndex >= $idx;
                                        $isCurrent = $currentStepIndex === $idx;
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold transition {{ $isCurrent && $isHired ? 'bg-emerald-600 text-white ring-4 ring-emerald-500/20' : ($isCurrent ? 'bg-brand-500 text-white ring-4 ring-brand-500/20' : ($isPassed ? 'bg-[#111111] text-white dark:bg-white dark:text-[#111111]' : 'border border-[#E5E5E5] bg-white text-[#A1A1A1] dark:border-[#262626] dark:bg-[#1A1A1A]')) }}">
                                            {{ $isPassed ? '✓' : $idx + 1 }}
                                        </div>
                                        <span class="mt-2 text-[11px] font-bold {{ $isCurrent && $isHired ? 'text-emerald-600 dark:text-emerald-400' : ($isCurrent ? 'text-brand-500' : ($isPassed ? 'text-[#111111] dark:text-white' : 'text-[#6B6B6B] dark:text-[#A1A1A1]')) }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Rejected Note --}}
                        <div class="py-4 border-b border-[#E5E5E5] dark:border-[#262626]">
                            <p class="text-xs text-red-600 dark:text-red-400">
                                This application has been marked as not moving forward at this time.
                            </p>
                        </div>
                    @endif

                    {{-- ========================================================= --}}
                    {{-- INTERVIEW CARD SECTION --}}
                    {{-- ========================================================= --}}
                    @if($application->interview)
                        <div class="mt-6 rounded-2xl border border-brand-500/30 bg-white p-5 dark:border-brand-500/30 dark:bg-[#1A1A1A]">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-500/10 text-lg font-bold text-brand-500">
                                        📹
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-bold text-[#111111] dark:text-white">
                                            Interview Assessment Round
                                        </h3>
                                        <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                            📅 {{ $application->interview->interview_date->format('d M Y') }} • {{ \Carbon\Carbon::parse($application->interview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($application->interview->end_time)->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>

                                <div>
                                    <span class="inline-flex rounded-full px-3 py-0.5 text-xs font-bold uppercase tracking-wider {{ $application->interview->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : ($application->interview->status === 'cancelled' ? 'bg-red-500/10 text-red-600 dark:text-red-400' : 'bg-brand-500/10 text-brand-500') }}">
                                        {{ $application->interview->status }}
                                    </span>
                                </div>
                            </div>

                            @if($application->interview->notes)
                                <p class="mt-3 text-xs text-[#6B6B6B] dark:text-[#A1A1A1] border-t border-[#E5E5E5] pt-3 dark:border-[#262626]">
                                    <span class="font-bold text-[#111111] dark:text-white">Interview Instructions:</span> {{ $application->interview->notes }}
                                </p>
                            @endif

                            @if($application->interview->status === 'scheduled' && $application->interview->meeting_link)
                                <div class="mt-4">
                                    <a
                                        href="{{ $application->interview->meeting_link }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-4 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
                                    >
                                        <span>📹 Join Google Meet</span>
                                        <span>↗</span>
                                    </a>
                                </div>
                            @endif

                            {{-- Completed Assessment & Admin Feedback Display --}}
                            @if($application->interview->status === 'completed')
                                <div class="mt-4 border-t border-[#E5E5E5] pt-4 dark:border-[#262626] space-y-2">
                                    <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5">
                                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 dark:text-emerald-300">
                                            <span>✓</span>
                                            <span>Interview round completed</span>
                                            @if($application->interview->feedback_submitted_at)
                                                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-normal">
                                                    ({{ $application->interview->feedback_submitted_at->format('d M Y, h:i A') }})
                                                </span>
                                            @endif
                                        </div>

                                        @if($application->interview->admin_feedback)
                                            <p class="mt-2 text-xs text-[#111111] dark:text-white">
                                                <span class="font-bold">Interviewer Assessment:</span> {{ $application->interview->admin_feedback }}
                                            </p>
                                        @endif

                                        @if($application->interview->feedback_attachment_path)
                                            <div class="mt-2 pt-2 border-t border-emerald-500/20 flex items-center justify-between">
                                                <span class="text-[11px] font-bold text-emerald-800 dark:text-emerald-300">Evaluation File:</span>
                                                <a
                                                    href="{{ asset('storage/' . $application->interview->feedback_attachment_path) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 text-xs font-bold text-brand-500 hover:underline"
                                                >
                                                    <span>View Attachment ↗</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>
                    @endif

                    {{-- ========================================================= --}}
                    {{-- EMPLOYMENT OFFER CARD (if generated/sent) --}}
                    {{-- ========================================================= --}}
                    @if($application->offer && in_array($application->offer->status, ['sent', 'accepted', 'declined']))
                        @php
                            $isOfferRevised = $application->offer->version > 1;
                        @endphp
                        <div class="mt-6 rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#1A1A1A] shadow-xs">
                            
                            {{-- Header --}}
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-xl">🎉</span>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-base font-bold text-[#111111] dark:text-white">
                                                Employment Offer
                                            </h3>
                                            @if($isHired)
                                                <span class="rounded-full bg-emerald-500/10 px-2 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                    ✓ Candidate Hired
                                                </span>
                                            @elseif($isOfferRevised)
                                                <span class="rounded-full bg-brand-500/10 px-2 py-0.5 text-[10px] font-bold text-brand-500 border border-brand-500/30">
                                                    Revised Offer (Version {{ $application->offer->version }})
                                                </span>
                                            @else
                                                <span class="rounded-full bg-slate-500/10 px-2 py-0.5 text-[10px] font-bold text-[#6B6B6B] dark:text-[#A1A1A1] border border-slate-500/20">
                                                    Version {{ $application->offer->version ?? 1 }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                            Official compensation and position terms
                                        </p>
                                    </div>
                                </div>

                                @php
                                    $offerStatusBadge = match($application->offer->status) {
                                        'sent' => 'border-blue-500/30 bg-blue-500/10 text-blue-600 dark:text-blue-400',
                                        'accepted' => 'border-emerald-500/30 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                        'declined' => 'border-red-500/30 bg-red-500/10 text-red-600 dark:text-red-400',
                                        default => 'border-slate-500/30 bg-slate-500/10 text-slate-600',
                                    };
                                    $offerLabel = match($application->offer->status) {
                                        'sent' => 'Offer Pending Review',
                                        'accepted' => 'Offer Accepted ✓',
                                        'declined' => 'Offer Declined ✕',
                                        default => ucfirst($application->offer->status),
                                    };
                                @endphp

                                <div class="flex items-center gap-2">
                                    <span class="inline-flex rounded-full border px-3 py-1 text-xs font-bold {{ $offerStatusBadge }}">
                                        {{ $offerLabel }}
                                    </span>
                                    <a
                                        href="{{ route('applications.offer.show', $application) }}"
                                        class="rounded-xl bg-[#111111] px-3 py-1.5 text-xs font-bold text-white hover:bg-brand-500 transition dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
                                    >
                                        Offer Hub →
                                    </a>
                                </div>
                            </div>

                            {{-- If Hired: Dedicated Confirmation Row --}}
                            @if($isHired)
                                <div class="mt-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-bold text-emerald-800 dark:text-emerald-200">
                                                ✓ Offer Formally Accepted & Signed Copy Confirmed
                                            </p>
                                            <p class="text-[11px] text-emerald-700 dark:text-emerald-400 mt-0.5">
                                                Employee Code: <strong class="font-mono">{{ $employee->employee_code }}</strong> • Scheduled Joining Date: <strong>{{ $employee->joining_date->format('d M Y') }}</strong>
                                            </p>
                                        </div>
                                        <a
                                            href="{{ route('applications.offer.download-signed', $application) }}"
                                            class="inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition"
                                        >
                                            <span>View Signed Document ↗</span>
                                        </a>
                                    </div>
                                </div>
                            @endif

                            {{-- Offer Metrics Grid --}}
                            <div class="mt-4 grid gap-3 sm:grid-cols-3">
                                <div class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3 dark:border-[#262626] dark:bg-[#141414]">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Annual CTC</p>
                                    <p class="mt-1 text-base font-extrabold text-[#111111] dark:text-white">
                                        ₹{{ number_format($application->offer->salary, 2) }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3 dark:border-[#262626] dark:bg-[#141414]">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Official Joining Date</p>
                                    <p class="mt-1 text-sm font-bold text-[#111111] dark:text-white">
                                        {{ $application->offer->joining_date ? $application->offer->joining_date->format('d M Y') : 'Immediate' }}
                                    </p>
                                </div>

                                <div class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3 dark:border-[#262626] dark:bg-[#141414]">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Offer Valid Until</p>
                                    <p class="mt-1 text-sm font-bold text-[#111111] dark:text-white">
                                        {{ $application->offer->offer_expiry_date ? $application->offer->offer_expiry_date->format('d M Y') : 'Open' }}
                                    </p>
                                </div>
                            </div>

                            @if(!$isHired)
                                {{-- Instructions Notice --}}
                                <div class="mt-4 rounded-xl border border-amber-500/30 bg-amber-500/10 p-3.5 text-xs text-amber-700 dark:text-amber-300">
                                    <strong>Next Step:</strong> Please download the offer letter, sign it, and upload the signed copy in the response section before accepting.
                                </div>
                            @endif

                            {{-- Signed Letter Status / Widget --}}
                            <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3.5 dark:border-[#262626] dark:bg-[#141414]">
                                <div>
                                    <p class="text-xs font-bold text-[#111111] dark:text-white">
                                        Signed Copy Status:
                                    </p>
                                    @if($application->offer->signed_offer_letter_path)
                                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5">
                                            ✓ Signed offer uploaded on {{ $application->offer->signed_at ? $application->offer->signed_at->format('d M Y') : 'Record' }}
                                        </p>
                                    @else
                                        <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1] mt-0.5">
                                            Not uploaded yet. Please sign and upload your PDF.
                                        </p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    @if($application->offer->signed_offer_letter_path)
                                        <a
                                            href="{{ route('applications.offer.download-signed', $application) }}"
                                            class="inline-flex items-center gap-1 rounded-xl bg-white px-3 py-1.5 text-xs font-bold text-[#111111] border border-[#E5E5E5] hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition"
                                        >
                                            View Signed Copy ↗
                                        </a>
                                    @endif
                                    @if(!$isHired)
                                        <a
                                            href="{{ route('applications.offer.show', $application) }}"
                                            class="inline-flex items-center gap-1 rounded-xl bg-brand-500 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-brand-600 transition"
                                        >
                                            Manage & Upload Signed PDF →
                                        </a>
                                    @endif
                                </div>
                            </div>

                            {{-- PDF & Response Actions --}}
                            <div class="mt-5 flex flex-wrap items-center gap-3 border-t border-[#E5E5E5] pt-4 dark:border-[#262626]">
                                @if($application->offer->offer_letter_path)
                                    <a
                                        href="{{ asset('storage/' . $application->offer->offer_letter_path) }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2 text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                                    >
                                        <span>📄 Preview PDF</span>
                                    </a>

                                    <a
                                        href="{{ route('applications.offer.download', $application) }}"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2 text-xs font-bold text-[#111111] transition hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                                    >
                                        <span>📥 Download Official Letter (v{{ $application->offer->version ?? 1 }})</span>
                                    </a>
                                @endif

                                {{-- Offer Decision Buttons (if sent and not accepted) --}}
                                @if($application->offer->status === 'sent')
                                    @if($application->offer->offer_expiry_date && now()->gt($application->offer->offer_expiry_date))
                                        <span class="text-xs font-bold text-red-500">
                                            This offer has expired.
                                        </span>
                                    @else
                                        <div class="flex items-center gap-2 sm:ml-auto">
                                            <form method="POST" action="{{ route('applications.offer.accept', $application) }}" onsubmit="return confirm('Are you sure you want to accept this employment offer?');">
                                                @csrf
                                                <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700">
                                                    Accept Offer ✓
                                                </button>
                                            </form>

                                            <button
                                                type="button"
                                                onclick="openDeclineModal({{ $application->id }})"
                                                class="rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-2 text-xs font-bold text-red-600 transition hover:bg-red-500/20 dark:text-red-400"
                                            >
                                                Decline
                                            </button>
                                        </div>
                                    @endif
                                @elseif($application->offer->status === 'declined' && $application->offer->decline_reason)
                                    <div class="w-full text-xs text-red-600 dark:text-red-400">
                                        <span class="font-bold">Declined Reason:</span> {{ $application->offer->decline_reason }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    @endif

                </div>
            @endforeach

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $applications->links() }}
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="mt-12 rounded-2xl border border-dashed border-[#E5E5E5] bg-[#F7F7F7] p-16 text-center dark:border-[#262626] dark:bg-[#141414]">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-3xl text-brand-500 dark:bg-brand-500/20">
                💼
            </span>
            <h3 class="mt-4 text-base font-bold text-[#111111] dark:text-white">
                No active applications yet
            </h3>
            <p class="mt-1.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1] max-w-sm mx-auto">
                Explore open engineering roles and submit your first application to begin tracking your hiring journey.
            </p>
            <a
                href="{{ route('jobs.index') }}"
                class="mt-5 inline-flex items-center gap-1 rounded-xl bg-brand-500 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-600"
            >
                Browse Open Roles →
            </a>
        </div>
    @endif

</div>

{{-- Global Decline Modal --}}
<div id="app-decline-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-2xl">
        <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
            <h2 class="text-base font-bold text-[#111111] dark:text-white">
                Decline Employment Offer
            </h2>
            <button onclick="closeDeclineModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <p class="mt-3 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Please provide the reason for declining the offer. This reason is required and will be shared with the recruitment team.
        </p>

        <form id="app-decline-form" method="POST" action="" class="mt-4 space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                    Reason for declining <span class="text-red-500">*</span>:
                </label>
                <textarea
                    name="decline_reason"
                    rows="4"
                    required
                    placeholder="Please specify why you are declining the offer..."
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3 text-xs text-[#111111] outline-none focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                ></textarea>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-2">
                <button
                    type="button"
                    onclick="closeDeclineModal()"
                    class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2 text-xs font-bold text-[#111111] hover:bg-slate-100 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="rounded-xl bg-red-600 py-2 text-xs font-bold text-white hover:bg-red-700 transition"
                >
                    Confirm Decline
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeclineModal(applicationId) {
        const form = document.getElementById('app-decline-form');
        form.action = `/applications/${applicationId}/offer/decline`;
        document.getElementById('app-decline-modal').classList.remove('hidden');
    }

    function closeDeclineModal() {
        document.getElementById('app-decline-modal').classList.add('hidden');
    }
</script>

@endsection