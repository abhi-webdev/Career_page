@extends('layouts.app')

@section('title', 'Official Offer - ' . $application->job->title)

@section('content')

<div class="mx-auto max-w-5xl px-4 py-10 sm:px-6 lg:px-8 space-y-8">

    {{-- Breadcrumb Back --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('dashboard') }}" class="hover:text-brand-500 transition">
            Dashboard
        </a>
        <span>/</span>
        <a href="{{ route('applications.index') }}" class="hover:text-brand-500 transition">
            My Applications
        </a>
        <span>/</span>
        <span class="text-[#111111] dark:text-white font-bold">Offer</span>
    </div>

    @php
        $offer = $application->offer;
        $isRevised = $offer && ($offer->version > 1);
        $status = strtolower($offer ? $offer->status : 'none');
        $isExpired = $offer && $offer->offer_expiry_date && now()->startOfDay()->gt($offer->offer_expiry_date);
        $employee = $application->employee;
        $isAccepted = $offer && ($offer->status === 'accepted');
    @endphp

    @if(!$offer)
        {{-- Empty State --}}
        <div class="rounded-2xl border border-dashed border-[#E5E5E5] bg-[#F7F7F7] p-16 text-center dark:border-[#262626] dark:bg-[#141414]">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-3xl text-brand-500 dark:bg-brand-500/20">
                🎉
            </span>
            <h2 class="mt-4 text-lg font-bold text-[#111111] dark:text-white">
                No active offer at this time
            </h2>
            <p class="mt-1.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1] max-w-sm mx-auto">
                When you successfully complete interviews and are selected for an engineering position, your official offer letter and compensation package will appear here.
            </p>
            <a
                href="{{ route('applications.index') }}"
                class="mt-5 inline-flex items-center gap-1 rounded-xl bg-brand-500 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-600"
            >
                View Applications →
            </a>
        </div>
    @else

        {{-- Top Banner --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                        {{ $application->job->company }}
                    </span>
                    @if($employee)
                        <span class="font-mono text-xs font-extrabold text-brand-500 bg-brand-500/10 px-2.5 py-0.5 rounded-lg border border-brand-500/20">
                            Employee ID: {{ $employee->employee_code }}
                        </span>
                    @elseif($isRevised)
                        <span class="rounded-full bg-brand-500/10 px-2.5 py-0.5 text-[10px] font-bold text-brand-500 border border-brand-500/30">
                            Revised Offer • Version {{ $offer->version }}
                        </span>
                    @else
                        <span class="rounded-full bg-blue-500/10 px-2.5 py-0.5 text-[10px] font-bold text-blue-600 dark:text-blue-400 border border-blue-500/30">
                            Version {{ $offer->version ?? 1 }}
                        </span>
                    @endif
                </div>

                <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                    Employment Offer for {{ $application->job->title }}
                </h1>
                <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Issued to <span class="font-bold text-[#111111] dark:text-white">{{ $application->user->name }}</span> ({{ $application->user->email }})
                </p>
            </div>

            @php
                $statusBadge = match($status) {
                    'sent' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/30',
                    'accepted' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/30',
                    'declined' => 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/30',
                    'draft' => 'bg-slate-500/10 text-slate-600 dark:text-slate-400 border-slate-500/30',
                    default => 'bg-slate-500/10 text-slate-500 border-slate-500/30',
                };
            @endphp
            <div>
                <span class="inline-flex rounded-full border px-4 py-1.5 text-xs font-bold capitalize {{ $statusBadge }}">
                    Status: {{ $offer->status === 'accepted' ? 'Offer Accepted' : $offer->status }}
                </span>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- POST-ACCEPTANCE EMPLOYEE HUB (IF ACCEPTED) --}}
        {{-- ========================================================= --}}
        @if($isAccepted && $employee)
            <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-6 sm:p-8 text-center space-y-4 shadow-xs">
                <span class="text-3xl">🎉</span>
                <h2 class="text-xl font-extrabold text-emerald-800 dark:text-emerald-300">
                    ✓ Offer Accepted • Welcome to the Team!
                </h2>
                <p class="text-xs text-emerald-700 dark:text-emerald-400 max-w-lg mx-auto leading-relaxed">
                    Congratulations! You have successfully accepted the official employment offer for <strong class="text-emerald-900 dark:text-emerald-200">{{ $application->job->title }}</strong> at <strong class="text-emerald-900 dark:text-emerald-200">{{ $application->job->company }}</strong>.
                </p>

                <div class="grid gap-3 sm:grid-cols-3 max-w-2xl mx-auto mt-4 text-xs">
                    <div class="bg-white/80 dark:bg-[#141414] p-4 rounded-xl border border-emerald-500/20">
                        <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-[#A1A1A1]">Employee ID</span>
                        <p class="font-mono font-extrabold text-brand-500 text-sm mt-0.5">{{ $employee->employee_code }}</p>
                    </div>
                    <div class="bg-white/80 dark:bg-[#141414] p-4 rounded-xl border border-emerald-500/20">
                        <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-[#A1A1A1]">Official Joining Date</span>
                        <p class="font-bold text-[#111111] dark:text-white text-sm mt-0.5">{{ $employee->joining_date->format('d M Y') }}</p>
                    </div>
                    <div class="bg-white/80 dark:bg-[#141414] p-4 rounded-xl border border-emerald-500/20">
                        <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-[#A1A1A1]">Signed Offer Status</span>
                        <p class="font-bold text-emerald-600 dark:text-emerald-400 text-sm mt-0.5">✓ Submitted</p>
                    </div>
                </div>

                <div class="pt-3 flex flex-wrap justify-center gap-3">
                    @if($offer->signed_offer_letter_path)
                        <a
                            href="{{ route('applications.offer.download-signed', $application) }}"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition"
                        >
                            <span>View Signed Offer Letter ↗</span>
                        </a>
                    @endif
                    <a
                        href="{{ route('applications.offer.download', $application) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-[#111111] border border-[#E5E5E5] hover:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white transition"
                    >
                        <span>Download Official Letter (v{{ $offer->version ?? 1 }}) 📥</span>
                    </a>
                </div>
            </div>
        @endif

        {{-- Revised Offer Notification Alert (if revised and still pending) --}}
        @if($isRevised && !$isAccepted)
            <div class="rounded-2xl border border-brand-500/30 bg-brand-500/10 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-brand-500 font-bold text-white text-sm">
                        ⚡
                    </span>
                    <div>
                        <h2 class="text-xs font-bold text-brand-600 dark:text-brand-400">
                            Revised Offer Notice (Version {{ $offer->version }})
                        </h2>
                        <p class="text-xs text-[#111111] dark:text-white mt-0.5">
                            Your employment offer has been revised with an updated joining date of 
                            <strong class="text-brand-500">{{ $offer->joining_date->format('d F Y') }}</strong>. 
                            Please download the revised offer letter, sign it, and upload the signed copy.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Offer Metrics 4-Box Grid --}}
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Salary --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414]">
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Annual CTC</p>
                <p class="mt-2 text-2xl font-extrabold text-[#111111] dark:text-white">
                    ₹{{ number_format($offer->salary, 2) }}
                </p>
                <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Gross base compensation</p>
            </div>

            {{-- Joining Date --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414]">
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Final Joining Date</p>
                <p class="mt-2 text-lg font-bold text-[#111111] dark:text-white">
                    {{ $offer->joining_date ? $offer->joining_date->format('d M Y') : 'Immediate' }}
                </p>
                <p class="mt-1 text-[11px] text-brand-500 font-semibold">
                    {{ $offer->joining_date ? $offer->joining_date->diffForHumans() : '' }}
                </p>
            </div>

            {{-- Offer Expiry --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414]">
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Offer Validity</p>
                <p class="mt-2 text-lg font-bold text-[#111111] dark:text-white">
                    {{ $offer->offer_expiry_date ? $offer->offer_expiry_date->format('d M Y') : 'No Expiry Set' }}
                </p>
                @if($isExpired)
                    <p class="mt-1 text-[11px] text-red-500 font-bold">Expired</p>
                @else
                    <p class="mt-1 text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                        {{ $isAccepted ? 'Accepted' : 'Valid' }}
                    </p>
                @endif
            </div>

            {{-- Version --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-5 dark:border-[#262626] dark:bg-[#141414]">
                <p class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Offer Version</p>
                <p class="mt-2 text-2xl font-extrabold text-brand-500">
                    v{{ $offer->version ?? 1 }}
                </p>
                <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $offer->versions->count() }} {{ Str::plural('revision', $offer->versions->count()) }} on record
                </p>
            </div>
        </div>

        {{-- Main Offer Sections: 2-Column Grid --}}
        <div class="grid gap-6 lg:grid-cols-12">

            {{-- Left Column: Letter Download & Signed Upload --}}
            <div class="lg:col-span-7 space-y-6">

                {{-- 1. Official Letter Download Card --}}
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                            1. Official Offer Letter
                        </h2>
                        <span class="text-xs text-brand-500 font-bold">PDF Document</span>
                    </div>

                    <p class="mt-4 text-xs text-[#6B6B6B] dark:text-[#A1A1A1] leading-relaxed">
                        Please download the official employment offer letter, review all terms and compensation details, sign the candidate acceptance section, and upload the signed copy below.
                    </p>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        @if($offer->offer_letter_path)
                            <a
                                href="{{ route('applications.offer.download', $application) }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-brand-500 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
                            >
                                <span>📥 Download Offer Letter (v{{ $offer->version }})</span>
                            </a>
                            <a
                                href="{{ asset('storage/' . $offer->offer_letter_path) }}"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs font-bold text-[#111111] transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                            >
                                <span>Preview PDF ↗</span>
                            </a>
                        @else
                            <p class="text-xs text-amber-500 font-semibold">
                                Offer letter is currently being generated by the administrator.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- 2. Signed Offer Letter Upload Section --}}
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                            2. Signed Offer Letter
                        </h2>
                        @if($offer->signed_offer_letter_path)
                            <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                ✓ Uploaded
                            </span>
                        @else
                            <span class="rounded-full bg-amber-500/10 px-2.5 py-0.5 text-[10px] font-bold text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                Pending Upload
                            </span>
                        @endif
                    </div>

                    @if($offer->signed_offer_letter_path)
                        {{-- Uploaded Status --}}
                        <div class="mt-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-500 font-bold text-white text-sm">
                                        ✓
                                    </span>
                                    <div>
                                        <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300">
                                            Signed offer uploaded successfully
                                        </p>
                                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400">
                                            Uploaded on {{ $offer->signed_at ? $offer->signed_at->format('d M Y, h:i A') : 'Recorded' }}
                                        </p>
                                    </div>
                                </div>

                                <a
                                    href="{{ route('applications.offer.download-signed', $application) }}"
                                    class="inline-flex items-center gap-1 rounded-xl bg-white px-3.5 py-1.5 text-xs font-bold text-[#111111] shadow-xs hover:bg-[#F7F7F7] transition"
                                >
                                    <span>Download Copy ↗</span>
                                </a>
                            </div>
                        </div>

                        {{-- Re-upload Form if still in sent state --}}
                        @if($offer->status === 'sent')
                            <details class="mt-4 text-xs">
                                <summary class="cursor-pointer text-brand-500 font-semibold hover:underline">
                                    Need to upload a revised signed copy?
                                </summary>
                                <form action="{{ route('applications.offer.upload-signed', $application) }}" method="POST" enctype="multipart/form-data" class="mt-3 space-y-3">
                                    @csrf
                                    <input
                                        type="file"
                                        name="signed_offer"
                                        accept="application/pdf"
                                        required
                                        class="block w-full text-xs text-[#6B6B6B] file:mr-4 file:rounded-xl file:border-0 file:bg-brand-500 file:px-4 file:py-2 file:text-xs file:font-bold file:text-white hover:file:bg-brand-600 cursor-pointer"
                                    >
                                    <button type="submit" class="rounded-xl bg-[#111111] px-4 py-2 text-xs font-bold text-white hover:bg-brand-500 transition dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white">
                                        Replace Signed PDF
                                    </button>
                                </form>
                            </details>
                        @endif
                    @else
                        {{-- Upload Required Notice & Form --}}
                        <div class="mt-4">
                            <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1] leading-relaxed">
                                <strong class="text-[#111111] dark:text-white">Note:</strong> Please download the offer letter, sign it, and upload the signed copy in the response section.
                            </p>

                            @if($offer->status === 'sent' && !$isExpired)
                                <form action="{{ route('applications.offer.upload-signed', $application) }}" method="POST" enctype="multipart/form-data" class="mt-4 space-y-3">
                                    @csrf
                                    <div class="rounded-xl border border-dashed border-[#E5E5E5] bg-[#F7F7F7] p-5 text-center dark:border-[#262626] dark:bg-[#1A1A1A]">
                                        <input
                                            type="file"
                                            name="signed_offer"
                                            id="signed_offer_file"
                                            accept="application/pdf"
                                            required
                                            class="block w-full text-xs text-[#6B6B6B] file:mr-4 file:rounded-xl file:border-0 file:bg-brand-500 file:px-4 file:py-2.5 file:text-xs file:font-bold file:text-white hover:file:bg-brand-600 cursor-pointer"
                                        >
                                        <p class="mt-2 text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                            Accepted format: PDF only (Maximum file size: 5MB)
                                        </p>
                                    </div>

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-brand-500 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
                                    >
                                        Upload Signed Offer Letter →
                                    </button>
                                </form>
                            @else
                                <p class="mt-3 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    Uploads are not active for this offer stage.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- 3. Offer Revisions History (if multiple versions exist) --}}
                @if($offer->versions->count() > 0)
                    <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                            Offer Revision History
                        </h2>
                        <div class="mt-4 divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                            @foreach($offer->versions as $v)
                                <div class="py-3 flex items-center justify-between text-xs">
                                    <div>
                                        <span class="font-bold text-[#111111] dark:text-white">Version {{ $v->version }}</span>
                                        <span class="ml-2 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                            Joining: {{ $v->joining_date ? $v->joining_date->format('d M Y') : 'N/A' }} • CTC: ₹{{ number_format($v->salary, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $v->status === 'accepted' ? 'bg-emerald-500/10 text-emerald-500' : ($v->status === 'declined' ? 'bg-red-500/10 text-red-500' : 'bg-slate-500/10 text-slate-500') }}">
                                            {{ $v->status }}
                                        </span>
                                        @if($v->offer_letter_path)
                                            <a href="{{ asset('storage/' . $v->offer_letter_path) }}" target="_blank" class="text-brand-500 font-bold hover:underline">
                                                PDF ↗
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Right Column: Joining Date Verification & Decision Actions --}}
            <div class="lg:col-span-5 space-y-6">

                {{-- 1. Joining Date Interactive Section --}}
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                        Scheduled Joining Date
                    </h2>

                    <div class="mt-4 space-y-3">
                        <div class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A]">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Proposed Joining Date</span>
                            <p class="text-lg font-bold text-brand-500 mt-0.5">
                                {{ $offer->joining_date ? $offer->joining_date->format('l, d F Y') : 'Immediate' }}
                            </p>
                        </div>

                        {{-- Pending Request Status if submitted --}}
                        @if($offer->joining_date_request_status === 'pending')
                            <div class="rounded-xl border border-amber-500/30 bg-amber-500/10 p-3.5 text-xs text-amber-700 dark:text-amber-300">
                                <p class="font-bold">⏳ Date Change Request Under Review</p>
                                <p class="mt-1 text-[11px]">
                                    Requested: <strong>{{ $offer->requested_joining_date ? $offer->requested_joining_date->format('d M Y') : 'N/A' }}</strong>
                                </p>
                                <p class="mt-0.5 text-[11px]">
                                    Reason: {{ $offer->joining_date_note }}
                                </p>
                            </div>
                        @endif

                        @if($offer->status === 'sent' && !$isExpired)
                            <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1] pt-1">
                                Can you join on this date?
                            </p>

                            <div class="space-y-2">
                                <button
                                    type="button"
                                    onclick="hideJoiningDateForm()"
                                    class="w-full rounded-xl border border-emerald-500/30 bg-emerald-500/10 py-2 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/20 transition"
                                >
                                    ✓ Yes, I can join on {{ $offer->joining_date ? $offer->joining_date->format('d M') : 'this date' }}
                                </button>

                                <button
                                    type="button"
                                    onclick="showJoiningDateForm()"
                                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2 text-xs font-bold text-[#111111] hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition"
                                >
                                    📅 I need a different joining date
                                </button>
                            </div>

                            {{-- Expandable Joining Date Change Form --}}
                            <div id="joining-date-form-container" class="hidden pt-3 border-t border-[#E5E5E5] dark:border-[#262626]">
                                <form action="{{ route('applications.offer.request-joining-date', $application) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                                            Requested Joining Date:
                                        </label>
                                        <input
                                            type="date"
                                            name="requested_joining_date"
                                            required
                                            class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3.5 py-2 text-xs text-[#111111] outline-none focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                                            Why can't you join on this date?
                                        </label>
                                        <textarea
                                            name="joining_date_note"
                                            rows="3"
                                            required
                                            placeholder="Provide reason for rescheduling joining date..."
                                            class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3 text-xs text-[#111111] outline-none focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                                        ></textarea>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button
                                            type="submit"
                                            class="flex-1 rounded-xl bg-brand-500 py-2 text-xs font-bold text-white shadow-xs hover:bg-brand-600 transition"
                                        >
                                            Submit Request →
                                        </button>
                                        <button
                                            type="button"
                                            onclick="hideJoiningDateForm()"
                                            class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-3 py-2 text-xs font-bold text-[#6B6B6B] hover:text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- 2. Final Decision Actions Card --}}
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                        Offer Decision
                    </h2>

                    @if($offer->status === 'sent')
                        @if($isExpired)
                            <div class="mt-4 rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-xs text-red-600 dark:text-red-400 font-bold">
                                ⚠️ This employment offer has expired.
                            </div>
                        @else
                            <div class="mt-4 space-y-3">
                                <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    Once you have signed and uploaded the offer letter, you can confirm your formal acceptance.
                                </p>

                                <form action="{{ route('applications.offer.accept', $application) }}" method="POST" onsubmit="return confirm('Are you sure you want to formally accept this employment offer?');">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-emerald-600 py-3 text-xs font-bold text-white shadow-xs transition hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-500/50"
                                    >
                                        Accept Employment Offer ✓
                                    </button>
                                </form>

                                <button
                                    type="button"
                                    onclick="showDeclineModal()"
                                    class="w-full rounded-xl border border-red-500/30 bg-red-500/10 py-2.5 text-xs font-bold text-red-600 hover:bg-red-500/20 dark:text-red-400 transition"
                                >
                                    Decline Offer ✕
                                </button>
                            </div>
                        @endif
                    @elseif($offer->status === 'accepted')
                        <div class="mt-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4 text-center">
                            <span class="text-2xl">🎉</span>
                            <h3 class="mt-2 text-sm font-bold text-emerald-700 dark:text-emerald-300">
                                Offer Accepted
                            </h3>
                            <p class="mt-1 text-xs text-emerald-600 dark:text-emerald-400">
                                Welcome to the team! Our onboarding team will connect with you soon before your joining date.
                            </p>
                        </div>
                    @elseif($offer->status === 'declined')
                        <div class="mt-4 rounded-xl border border-red-500/30 bg-red-500/10 p-4">
                            <h3 class="text-xs font-bold text-red-600 dark:text-red-400">
                                Offer Declined
                            </h3>
                            @if($offer->decline_reason)
                                <p class="mt-1 text-xs text-[#111111] dark:text-white">
                                    <span class="font-bold">Reason:</span> {{ $offer->decline_reason }}
                                </p>
                            @endif
                            <p class="mt-1 text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                Declined on {{ $offer->declined_at ? $offer->declined_at->format('d M Y, h:i A') : 'Recorded' }}
                            </p>
                        </div>
                    @endif
                </div>

            </div>

        </div>

    @endif

</div>

{{-- Decline Reason Modal --}}
<div id="decline-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-2xl">
        <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
            <h2 class="text-base font-bold text-[#111111] dark:text-white">
                Decline Employment Offer
            </h2>
            <button onclick="hideDeclineModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <p class="mt-3 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Please provide the reason for declining the offer. This reason will be shared with the recruitment team.
        </p>

        @if($offer)
            <form action="{{ route('applications.offer.decline', $application) }}" method="POST" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1">
                        Reason for declining <span class="text-red-500">*</span>:
                    </label>
                    <textarea
                        name="decline_reason"
                        rows="4"
                        required
                        placeholder="Please elaborate on your reason (e.g. accepted another position, compensation, timing)..."
                        class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3 text-xs text-[#111111] outline-none focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    ></textarea>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-2">
                    <button
                        type="button"
                        onclick="hideDeclineModal()"
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
        @endif
    </div>
</div>

<script>
    function showJoiningDateForm() {
        document.getElementById('joining-date-form-container').classList.remove('hidden');
    }

    function hideJoiningDateForm() {
        document.getElementById('joining-date-form-container').classList.add('hidden');
    }

    function showDeclineModal() {
        document.getElementById('decline-modal').classList.remove('hidden');
    }

    function hideDeclineModal() {
        document.getElementById('decline-modal').classList.add('hidden');
    }
</script>

@endsection
