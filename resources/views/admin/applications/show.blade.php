@extends('layouts.admin')

@section('title', 'Candidate Review: ' . $application->user->name)
@section('header_title', 'Candidate Evaluation')

@section('content')

@php
    $offer = $application->offer;
    $isOfferAccepted = $offer && ($offer->status === 'accepted');
    $isSignedUploaded = $offer && !empty($offer->signed_offer_letter_path);
    $employee = $application->employee;
    $isHired = $isOfferAccepted && $isSignedUploaded;
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Breadcrumb Back --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('admin.applications.index') }}" class="hover:text-brand-500 transition">
            Applications
        </a>
        <span>/</span>
        <span class="text-[#111111] dark:text-white font-bold">{{ $application->user->name }}</span>
    </div>

    {{-- Header Banner --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                    Application Profile
                </span>
                @if($employee)
                    <span class="font-mono text-xs font-extrabold text-brand-500 bg-brand-500/10 px-2 py-0.5 rounded-lg border border-brand-500/20">
                        Employee ID: {{ $employee->employee_code }}
                    </span>
                @endif
            </div>

            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                {{ $application->user->name }}
            </h1>
            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                {{ $application->user->email }} • Applied for <span class="font-bold text-[#111111] dark:text-white">{{ $application->job->title }}</span> ({{ $application->job->company }})
            </p>
        </div>

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
        <div class="flex items-center gap-2">
            @if($employee)
                <a
                    href="{{ route('admin.employees.show', $employee) }}"
                    class="inline-flex items-center gap-1 rounded-xl bg-brand-500 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-brand-600 transition"
                >
                    <span>👔 View Employee Profile</span>
                    <span>→</span>
                </a>
            @endif
            <span class="inline-flex rounded-full border px-4 py-1.5 text-xs font-bold capitalize {{ $statusBadge }}">
                Stage: {{ $application->status }}
            </span>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- COMPLETE RECRUITMENT LIFECYCLE AUDIT BANNER (IF HIRED) --}}
    {{-- ========================================================= --}}
    @if($isHired && $employee)
        <div class="rounded-2xl border border-emerald-500/30 bg-emerald-500/10 p-5 shadow-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-emerald-600 text-sm font-bold text-white">
                            ✓
                        </span>
                        <h2 class="text-sm font-extrabold text-emerald-800 dark:text-emerald-300">
                            Recruitment Lifecycle Successfully Completed • Candidate Hired
                        </h2>
                    </div>

                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs font-bold text-emerald-700 dark:text-emerald-300">
                        <span class="bg-emerald-600/15 px-2.5 py-1 rounded-lg">✓ Selected</span>
                        <span class="text-emerald-400">→</span>
                        <span class="bg-emerald-600/15 px-2.5 py-1 rounded-lg">✓ Offer Sent</span>
                        <span class="text-emerald-400">→</span>
                        <span class="bg-emerald-600/15 px-2.5 py-1 rounded-lg">✓ Signed Offer Received</span>
                        <span class="text-emerald-400">→</span>
                        <span class="bg-emerald-600/15 px-2.5 py-1 rounded-lg">✓ Offer Accepted</span>
                        <span class="text-emerald-400">→</span>
                        <span class="bg-emerald-600 text-white px-3 py-1 rounded-lg">✓ Employee Created ({{ $employee->employee_code }})</span>
                    </div>

                    <div class="mt-3 grid gap-2 sm:grid-cols-2 text-xs text-emerald-800 dark:text-emerald-200">
                        <p><strong>Employee ID:</strong> <span class="font-mono font-bold">{{ $employee->employee_code }}</span></p>
                        <p><strong>Official Joining Date:</strong> <span class="font-bold">{{ $employee->joining_date->format('d M Y') }}</span></p>
                    </div>
                </div>

                <div class="shrink-0">
                    <a
                        href="{{ route('admin.employees.show', $employee) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition"
                    >
                        <span>Open Staff Record</span>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- Main 2-Column Grid --}}
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- Left Column: Candidate & Job Details --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- 1. Candidate Info Card --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Candidate Details
                </h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 text-xs">
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Full Name</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Email Address</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->user->email }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Applied Date</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $application->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Candidate Role</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5 capitalize">
                            {{ $employee ? 'Employee' : $application->user->role }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- 2. Signed Offer Letter Card (Visible as soon as signed document is uploaded) --}}
            @if($offer && $offer->signed_offer_letter_path)
                <div class="rounded-2xl border border-emerald-500/30 bg-white p-6 dark:border-emerald-500/30 dark:bg-[#141414] shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                            Signed Offer Letter
                        </h2>
                        <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                            ✓ Signed Offer Received
                        </span>
                    </div>

                    <div class="mt-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-600 text-sm font-bold text-white shadow-xs">
                                    ✓
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-emerald-800 dark:text-emerald-200">
                                        Candidate Signed Offer Letter (PDF)
                                    </p>
                                    <p class="text-[11px] text-emerald-700 dark:text-emerald-400">
                                        Uploaded: {{ $offer->signed_at ? $offer->signed_at->format('d F Y, h:i A') : 'Recorded' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ asset('storage/' . $offer->signed_offer_letter_path) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 rounded-xl bg-white px-3.5 py-2 text-xs font-bold text-[#111111] shadow-xs hover:bg-[#F7F7F7] transition"
                                >
                                    <span>View Signed Offer Letter ↗</span>
                                </a>

                                <a
                                    href="{{ route('admin.applications.offer.download-signed', $application) }}"
                                    class="inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition"
                                >
                                    <span>Download Signed Offer Letter 📥</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- 3. Resume Card --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Attached Resume
                    </h2>
                    @if($application->resume)
                        <span class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                            Uploaded {{ $application->resume->created_at->diffForHumans() }}
                        </span>
                    @endif
                </div>

                @if($application->resume)
                    <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A]">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-red-500/10 text-sm font-bold text-red-500">
                                PDF
                            </span>
                            <div>
                                <p class="text-xs font-bold text-[#111111] dark:text-white truncate max-w-xs">
                                    {{ $application->resume->file_name }}
                                </p>
                                <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">Verified document</p>
                            </div>
                        </div>

                        <a
                            href="{{ asset('storage/' . $application->resume->file_path) }}"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded-xl bg-[#111111] px-4 py-2 text-xs font-bold text-white transition hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
                        >
                            <span>Open Resume</span>
                            <span>↗</span>
                        </a>
                    </div>
                @else
                    <p class="mt-4 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                        No resume document attached to this application.
                    </p>
                @endif
            </div>

            {{-- 4. Cover Letter --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Cover Letter / Candidate Notes
                </h2>
                @if($application->cover_letter)
                    <div class="mt-4 rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A]">
                        <p class="whitespace-pre-line text-xs leading-relaxed text-[#111111] dark:text-white font-sans">
                            {{ $application->cover_letter }}
                        </p>
                    </div>
                @else
                    <p class="mt-4 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Candidate did not include a cover letter.
                    </p>
                @endif
            </div>

            {{-- 5. Offer Revisions History (Admin View) --}}
            @if($offer && $offer->versions->count() > 0)
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                        Offer Revision History Audit
                    </h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-[#E5E5E5] text-[#6B6B6B] dark:border-[#262626] dark:text-[#A1A1A1]">
                                    <th class="pb-2 font-bold">Version</th>
                                    <th class="pb-2 font-bold">Salary</th>
                                    <th class="pb-2 font-bold">Joining Date</th>
                                    <th class="pb-2 font-bold">Status</th>
                                    <th class="pb-2 font-bold text-right">Letter PDF</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                                @foreach($offer->versions as $v)
                                    <tr>
                                        <td class="py-2.5 font-bold text-[#111111] dark:text-white">v{{ $v->version }}</td>
                                        <td class="py-2.5 text-[#111111] dark:text-white">₹{{ number_format($v->salary, 2) }}</td>
                                        <td class="py-2.5 text-[#111111] dark:text-white">{{ $v->joining_date ? $v->joining_date->format('d M Y') : 'N/A' }}</td>
                                        <td class="py-2.5">
                                            <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $v->status === 'accepted' ? 'bg-emerald-500/10 text-emerald-600' : ($v->status === 'declined' ? 'bg-red-500/10 text-red-600' : 'bg-slate-500/10 text-slate-600') }}">
                                                {{ $v->status }}
                                            </span>
                                        </td>
                                        <td class="py-2.5 text-right">
                                            @if($v->offer_letter_path)
                                                <a href="{{ asset('storage/' . $v->offer_letter_path) }}" target="_blank" class="text-brand-500 font-bold hover:underline">
                                                    Download ↗
                                                </a>
                                            @else
                                                <span class="text-slate-400">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: ATS Action Controls --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- 1. Stage Control Card (Hidden mutating actions when hired) --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                    Application Stage
                </h2>
                @if($isHired)
                    <div class="mt-3 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5 text-xs text-emerald-700 dark:text-emerald-300 font-semibold">
                        ✓ Candidate has completed all recruitment stages and is officially hired.
                    </div>
                @else
                    <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Transition this candidate through the hiring pipeline.
                    </p>

                    <form action="{{ route('admin.applications.status', $application) }}" method="POST" class="mt-4">
                        @csrf
                        @method('PATCH')

                        <select
                            name="status"
                            class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs font-bold text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                        >
                            <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending Screening</option>
                            <option value="shortlisted" {{ $application->status === 'shortlisted' ? 'selected' : '' }}>Shortlisted for Evaluation</option>
                            <option value="interview" {{ $application->status === 'interview' ? 'selected' : '' }}>Interview Stage</option>
                            <option value="selected" {{ $application->status === 'selected' ? 'selected' : '' }}>Selected for Offer</option>
                            <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected / Closed</option>
                        </select>

                        <button
                            type="submit"
                            class="mt-3 w-full rounded-xl bg-brand-500 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                        >
                            Save Stage Decision
                        </button>
                    </form>
                @endif
            </div>

            {{-- 2. Interview Scheduling & Admin Feedback Control --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Interview Assessment
                    </h2>
                    @if($application->interview)
                        <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $application->interview->status === 'completed' ? 'bg-emerald-500/10 text-emerald-500' : ($application->interview->status === 'cancelled' ? 'bg-red-500/10 text-red-500' : 'bg-brand-500/10 text-brand-500') }}">
                            {{ $application->interview->status }}
                        </span>
                    @endif
                </div>

                @if($application->interview)
                    <div class="mt-4 space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Candidate</span>
                            <span class="font-bold text-[#111111] dark:text-white">{{ $application->user->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Interview Date & Time</span>
                            <span class="font-bold text-[#111111] dark:text-white">
                                {{ $application->interview->interview_date->format('d M Y') }} • {{ \Carbon\Carbon::parse($application->interview->start_time)->format('h:i A') }}
                            </span>
                        </div>

                        @if($application->interview->meeting_link)
                            <a
                                href="{{ $application->interview->meeting_link }}"
                                target="_blank"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-brand-500/30 bg-brand-500/10 py-2 text-xs font-bold text-brand-500 hover:bg-brand-500 hover:text-white transition"
                            >
                                <span>📹 Open Google Meet</span>
                                <span>↗</span>
                            </a>
                        @endif

                        {{-- Completed Status & Admin Feedback Display --}}
                        @if($application->interview->status === 'completed')
                            <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3.5 mt-3 space-y-2">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-300">
                                    ✓ Interview Completed & Evaluated
                                </p>

                                @if($application->interview->admin_feedback)
                                    <div>
                                        <span class="text-[11px] font-bold text-[#111111] dark:text-white">Admin Feedback Note:</span>
                                        <p class="mt-0.5 text-xs text-[#111111] dark:text-white whitespace-pre-line">
                                            {{ $application->interview->admin_feedback }}
                                        </p>
                                    </div>
                                @endif

                                @if($application->interview->feedback_attachment_path)
                                    <div class="pt-1.5 border-t border-emerald-500/20 flex items-center justify-between">
                                        <span class="text-[11px] font-bold text-emerald-800 dark:text-emerald-300">Evaluation Attachment:</span>
                                        <a
                                            href="{{ route('admin.applications.interview.download-attachment', $application) }}"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-brand-500 hover:underline"
                                        >
                                            <span>Download File 📥</span>
                                        </a>
                                    </div>
                                @endif

                                <p class="text-[10px] text-emerald-600 dark:text-emerald-400">
                                    Recorded on {{ $application->interview->feedback_submitted_at ? $application->interview->feedback_submitted_at->format('d M Y, h:i A') : $application->interview->updated_at->format('d M Y, h:i A') }}
                                </p>
                            </div>
                        @endif

                        {{-- Action Controls when Scheduled --}}
                        @if($application->interview->status === 'scheduled')
                            <div class="grid grid-cols-2 gap-2 pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                                <a
                                    href="{{ route('admin.applications.interview.create', $application) }}"
                                    class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2 text-center text-xs font-bold text-[#111111] hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition"
                                >
                                    Reschedule
                                </a>

                                <button
                                    type="button"
                                    onclick="toggleCompleteInterviewForm()"
                                    class="rounded-xl bg-emerald-600 py-2 text-xs font-bold text-white transition hover:bg-emerald-700"
                                >
                                    Complete Round ✓
                                </button>
                            </div>

                            {{-- Admin Feedback & Attachment Form for Completion --}}
                            <div id="admin-complete-interview-container" class="hidden rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A] mt-3">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-2">
                                    Complete Interview & Provide Evaluation
                                </h3>

                                <form action="{{ route('admin.applications.interview.complete', $application) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            Admin Feedback Note:
                                        </label>
                                        <textarea
                                            name="admin_feedback"
                                            rows="3"
                                            placeholder="Enter technical interview evaluation notes, candidate strengths, and recommendations..."
                                            class="w-full rounded-xl border border-[#E5E5E5] bg-white p-2.5 text-xs text-[#111111] outline-none focus:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                                        ></textarea>
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            Feedback Attachment (Optional - PDF, DOC, PNG, JPG):
                                        </label>
                                        <input
                                            type="file"
                                            name="feedback_attachment"
                                            accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
                                            class="block w-full text-xs text-[#6B6B6B] file:mr-3 file:rounded-xl file:border-0 file:bg-brand-500 file:px-3 file:py-1.5 file:text-xs file:font-bold file:text-white hover:file:bg-brand-600 cursor-pointer"
                                        >
                                    </div>

                                    <div class="flex items-center gap-2 pt-1">
                                        <button
                                            type="submit"
                                            class="flex-1 rounded-xl bg-emerald-600 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition"
                                        >
                                            Save & Mark Completed ✓
                                        </button>
                                        <button
                                            type="button"
                                            onclick="toggleCompleteInterviewForm()"
                                            class="rounded-xl border border-[#E5E5E5] bg-white px-3 py-2 text-xs font-bold text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <form action="{{ route('admin.applications.interview.cancel', $application) }}" method="POST" onsubmit="return confirm('Cancel this scheduled interview?');" class="mt-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full rounded-xl border border-red-500/30 bg-red-500/10 py-1.5 text-xs font-bold text-red-600 hover:bg-red-500/20 dark:text-red-400 transition">
                                    Cancel Interview
                                </button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="mt-4 text-center">
                        <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                            No interview has been scheduled yet.
                        </p>
                        @if(!$isHired)
                            <a
                                href="{{ route('admin.applications.interview.create', $application) }}"
                                class="mt-3 inline-flex items-center gap-1 rounded-xl bg-[#111111] px-4 py-2 text-xs font-bold text-white transition hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
                            >
                                + Schedule Interview
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- 3. Offer Management & Contextual Visibility --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Employment Offer
                    </h2>
                    @if($offer)
                        <div class="flex items-center gap-1.5">
                            <span class="rounded-full bg-slate-500/10 px-2 py-0.5 text-[10px] font-bold text-[#6B6B6B] dark:text-[#A1A1A1]">
                                v{{ $offer->version ?? 1 }}
                            </span>
                            <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $offer->status === 'accepted' ? 'bg-emerald-500/10 text-emerald-500' : ($offer->status === 'declined' ? 'bg-red-500/10 text-red-500' : 'bg-blue-500/10 text-blue-500') }}">
                                {{ $offer->status === 'accepted' ? 'Offer Accepted' : $offer->status }}
                            </span>
                        </div>
                    @endif
                </div>

                @if($offer)
                    <div class="mt-4 space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Annual CTC</span>
                            <span class="font-bold text-[#111111] dark:text-white">₹{{ number_format($offer->salary, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Final Joining Date</span>
                            <span class="font-bold text-brand-500">{{ $offer->joining_date ? $offer->joining_date->format('d M Y') : 'N/A' }}</span>
                        </div>
                        @if($offer->offer_expiry_date)
                            <div class="flex justify-between">
                                <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Expiry Date</span>
                                <span class="font-bold text-[#111111] dark:text-white">{{ $offer->offer_expiry_date->format('d M Y') }}</span>
                            </div>
                        @endif

                        {{-- Status Badges --}}
                        <div class="pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                            @if($isOfferAccepted && $isSignedUploaded)
                                <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3 space-y-1.5">
                                    <p class="text-xs font-bold text-emerald-700 dark:text-emerald-300">
                                        ✓ Offer Accepted & Candidate Hired
                                    </p>
                                    <p class="text-[11px] text-emerald-600 dark:text-emerald-400">
                                        Signed offer uploaded on {{ $offer->signed_at ? $offer->signed_at->format('d M Y') : 'Record' }}
                                    </p>
                                </div>
                            @elseif($offer->status === 'sent')
                                <div class="rounded-xl border border-blue-500/30 bg-blue-500/10 p-3 space-y-1">
                                    <p class="text-xs font-bold text-blue-600 dark:text-blue-400">
                                        Offer Sent • Awaiting Candidate Response
                                    </p>
                                    @if($offer->signed_offer_letter_path)
                                        <p class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">
                                            ✓ Signed Offer Received
                                        </p>
                                    @endif
                                </div>
                            @elseif($offer->status === 'declined')
                                <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-3 text-xs text-red-700 dark:text-red-300">
                                    <p class="font-bold uppercase tracking-wide text-[10px]">Offer Declined</p>
                                    <p class="mt-1 font-semibold">Reason: {{ $offer->decline_reason ?? 'None provided' }}</p>
                                </div>
                            @endif
                        </div>

                        {{-- Original Offer Letter Document --}}
                        @if($offer->offer_letter_path)
                            <div class="pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                                <a
                                    href="{{ asset('storage/' . $offer->offer_letter_path) }}"
                                    target="_blank"
                                    class="block w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2 text-center text-xs font-bold text-[#111111] hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition"
                                >
                                    📄 Original Offer Letter PDF (v{{ $offer->version ?? 1 }}) ↗
                                </a>
                            </div>
                        @endif

                        {{-- ========================================================= --}}
                        {{-- CONTEXTUAL ACTION VISIBILITY (HIDDEN WHEN ACCEPTED/HIRED) --}}
                        {{-- ========================================================= --}}
                        @if(!$isOfferAccepted)

                            {{-- Joining Date Request Review (if active) --}}
                            @if($offer->joining_date_request_status === 'pending' || $offer->requested_joining_date)
                                <div class="rounded-xl border border-brand-500/30 bg-brand-500/10 p-3.5">
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-brand-600 dark:text-brand-400">
                                        Joining Date Change Request
                                    </p>
                                    <div class="mt-2 space-y-1 text-xs">
                                        <p><span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Current:</span> <strong class="text-[#111111] dark:text-white">{{ $offer->joining_date ? $offer->joining_date->format('d M Y') : 'N/A' }}</strong></p>
                                        <p><span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Requested:</span> <strong class="text-brand-500">{{ $offer->requested_joining_date ? $offer->requested_joining_date->format('d M Y') : 'N/A' }}</strong></p>
                                        <p><span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Reason:</span> <span class="italic text-[#111111] dark:text-white">{{ $offer->joining_date_note }}</span></p>
                                    </div>

                                    <button
                                        type="button"
                                        onclick="toggleReviseForm()"
                                        class="mt-3 w-full rounded-xl bg-brand-500 py-2 text-xs font-bold text-white shadow-xs hover:bg-brand-600 transition"
                                    >
                                        Review Request & Revise Offer →
                                    </button>
                                </div>
                            @endif

                            {{-- Collapsible Revise Offer Form --}}
                            <div id="admin-revise-offer-container" class="hidden rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-4 dark:border-[#262626] dark:bg-[#1A1A1A]">
                                <h3 class="text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-2">
                                    Generate Revised Offer (Version {{ ($offer->version ?? 1) + 1 }})
                                </h3>
                                <form action="{{ route('admin.applications.offer.revise', $application) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            New Suitable Joining Date:
                                        </label>
                                        <input
                                            type="date"
                                            name="joining_date"
                                            value="{{ $offer->requested_joining_date ? $offer->requested_joining_date->format('Y-m-d') : ($offer->joining_date ? $offer->joining_date->format('Y-m-d') : '') }}"
                                            required
                                            class="w-full rounded-xl border border-[#E5E5E5] bg-white px-3 py-2 text-xs text-[#111111] outline-none focus:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            Annual Salary (₹):
                                        </label>
                                        <input
                                            type="number"
                                            step="0.01"
                                            name="salary"
                                            value="{{ $offer->salary }}"
                                            class="w-full rounded-xl border border-[#E5E5E5] bg-white px-3 py-2 text-xs text-[#111111] outline-none focus:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                                        >
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-[#111111] dark:text-white mb-1">
                                            Revision Notes:
                                        </label>
                                        <textarea
                                            name="notes"
                                            rows="2"
                                            placeholder="Optional revision notes..."
                                            class="w-full rounded-xl border border-[#E5E5E5] bg-white p-2.5 text-xs text-[#111111] outline-none focus:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                                        >{{ $offer->notes }}</textarea>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button
                                            type="submit"
                                            class="flex-1 rounded-xl bg-brand-500 py-2 text-xs font-bold text-white hover:bg-brand-600 transition"
                                        >
                                            Generate Revised Offer
                                        </button>
                                        <button
                                            type="button"
                                            onclick="toggleReviseForm()"
                                            class="rounded-xl border border-[#E5E5E5] bg-white px-3 py-2 text-xs font-bold text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- PDF Generation & Send Offer (if in draft or not generated) --}}
                            @if(!$offer->offer_letter_path)
                                <form action="{{ route('admin.applications.offer.generate-letter', $application) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl bg-[#111111] py-2 text-xs font-bold text-white transition hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white">
                                        Generate Letter PDF ⚙
                                    </button>
                                </form>
                            @elseif($offer->status === 'draft')
                                <form action="{{ route('admin.applications.offer.send', $application) }}" method="POST" onsubmit="return confirm('Send this offer letter to candidate via email?');">
                                    @csrf
                                    <button type="submit" class="w-full rounded-xl bg-brand-500 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600">
                                        Send Offer to Candidate →
                                    </button>
                                </form>
                            @endif

                            @if($offer->status === 'sent')
                                <button
                                    type="button"
                                    onclick="toggleReviseForm()"
                                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2 text-xs font-bold text-[#111111] hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition"
                                >
                                    + Revise Offer / Change Date
                                </button>
                            @endif

                        @endif
                    </div>
                @else
                    {{-- No Offer Drafted yet --}}
                    <div class="mt-4 text-center">
                        <p class="text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                            No offer drafted yet.
                        </p>
                        @if($application->status === 'selected')
                            <a
                                href="{{ route('admin.applications.offer.create', $application) }}"
                                class="mt-3 inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white transition hover:bg-emerald-700"
                            >
                                + Create Offer Draft
                            </a>
                        @else
                            <p class="mt-2 text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                (Select candidate first to draft an offer)
                            </p>
                        @endif
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>

<script>
    function toggleCompleteInterviewForm() {
        const container = document.getElementById('admin-complete-interview-container');
        if (container) {
            container.classList.toggle('hidden');
        }
    }

    function toggleReviseForm() {
        const container = document.getElementById('admin-revise-offer-container');
        if (container) {
            container.classList.toggle('hidden');
        }
    }
</script>

@endsection