@extends('layouts.admin')

@section('title', 'Employee Profile: ' . $employee->user->name)
@section('header_title', 'Staff Member Overview')

@section('content')

@php
    $currentRole = $employee->user->role ?? 'employee';
    $roleBadge = match($currentRole) {
        'admin' => 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20',
        'hr' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400 border-purple-500/20',
        'tr' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20',
        default => 'bg-brand-500/10 text-brand-600 dark:text-brand-400 border-brand-500/20',
    };
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Breadcrumb Back --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('admin.employees.index') }}" class="hover:text-brand-500 transition">
            Employees
        </a>
        <span>/</span>
        <span class="text-[#111111] dark:text-white font-bold">{{ $employee->user->name }} ({{ $employee->employee_code }})</span>
    </div>

    {{-- Header Banner --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <div>
            <div class="flex items-center gap-2">
                <span class="font-mono text-xs font-extrabold text-brand-500 bg-brand-500/10 px-2.5 py-0.5 rounded-lg border border-brand-500/20">
                    {{ $employee->employee_code }}
                </span>
                <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $roleBadge }}">
                    Role: {{ $currentRole }}
                </span>
                <span class="text-xs font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">
                    {{ $employee->application->job->company }}
                </span>
            </div>

            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                {{ $employee->user->name }}
            </h1>
            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                {{ $employee->user->email }} • Position: <strong class="text-[#111111] dark:text-white">{{ $employee->application->job->title }}</strong>
            </p>
        </div>

        @php
            $empStatus = strtolower($employee->status);
            $statusBadge = match($empStatus) {
                'pending' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                'active' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                'inactive' => 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20',
                default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
            };
        @endphp

        <div class="flex items-center gap-3">
            <span class="inline-flex rounded-full border px-4 py-1.5 text-xs font-bold capitalize {{ $statusBadge }}">
                Status: {{ $employee->status }}
            </span>
            <a
                href="{{ route('admin.applications.show', $employee->application) }}"
                class="inline-flex items-center gap-1 rounded-xl bg-[#111111] px-4 py-2 text-xs font-bold text-white hover:bg-brand-500 transition dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
            >
                <span>ATS Application Profile</span>
                <span>↗</span>
            </a>
        </div>
    </div>

    {{-- Main 2-Column Grid --}}
    <div class="grid gap-6 lg:grid-cols-12">

        {{-- Left Column: Core Employee & Onboarding Details --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- 1. Employee Profile Card --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Employment Information
                </h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 text-xs">
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Employee Code</span>
                        <p class="font-extrabold text-brand-500 font-mono text-sm mt-0.5">{{ $employee->employee_code }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Candidate Name</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $employee->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Email Address</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $employee->user->email }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Assigned System Role</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5 uppercase tracking-wide">
                            {{ $employee->user->role }}
                        </p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Position & Department</span>
                        <p class="font-bold text-[#111111] dark:text-white mt-0.5">{{ $employee->application->job->title }}</p>
                    </div>
                    <div>
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Official Joining Date</span>
                        <p class="font-bold text-brand-500 mt-0.5">{{ $employee->joining_date->format('d M Y') }} ({{ $employee->joining_date->diffForHumans() }})</p>
                    </div>
                </div>
            </div>

            {{-- 2. Signed Offer Letter Document Card --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Signed Offer Document
                    </h2>
                    <span class="rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                        ✓ Signed Copy Verified
                    </span>
                </div>

                @if($employee->offer && $employee->offer->signed_offer_letter_path)
                    <div class="mt-4 rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-600 text-base font-bold text-white shadow-xs">
                                    ✓
                                </span>
                                <div>
                                    <p class="text-xs font-bold text-emerald-800 dark:text-emerald-200">
                                        Candidate Signed Offer Letter (PDF)
                                    </p>
                                    <p class="text-[11px] text-emerald-700 dark:text-emerald-400">
                                        Uploaded on {{ $employee->offer->signed_at ? $employee->offer->signed_at->format('d M Y, h:i A') : 'Recorded' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a
                                    href="{{ asset('storage/' . $employee->offer->signed_offer_letter_path) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-1 rounded-xl bg-white px-3.5 py-2 text-xs font-bold text-[#111111] shadow-xs hover:bg-[#F7F7F7] transition"
                                >
                                    <span>Preview ↗</span>
                                </a>

                                <a
                                    href="{{ route('admin.employees.signed-offer', $employee) }}"
                                    class="inline-flex items-center gap-1 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-emerald-700 transition"
                                >
                                    <span>Download PDF 📥</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="mt-4 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Signed document not found on file.
                    </p>
                @endif
            </div>

            {{-- 3. Application Summary --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    Application & Resume Audit
                </h2>
                <div class="mt-4 space-y-3 text-xs">
                    <div class="flex justify-between">
                        <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Application Date:</span>
                        <span class="font-bold text-[#111111] dark:text-white">{{ $employee->application->created_at->format('d M Y') }}</span>
                    </div>

                    @if($employee->application->resume)
                        <div class="flex items-center justify-between pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Attached Resume:</span>
                            <a
                                href="{{ asset('storage/' . $employee->application->resume->file_path) }}"
                                target="_blank"
                                class="text-brand-500 font-bold hover:underline inline-flex items-center gap-1"
                            >
                                <span>{{ $employee->application->resume->file_name }}</span>
                                <span>↗</span>
                            </a>
                        </div>
                    @endif

                    @if($employee->application->cover_letter)
                        <div class="pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1] block mb-1 font-semibold">Cover Letter:</span>
                            <p class="whitespace-pre-line text-xs bg-[#F7F7F7] dark:bg-[#1A1A1A] p-3 rounded-xl">
                                {{ $employee->application->cover_letter }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. Role Change Audit Trail --}}
            @if($employee->roleChangeLogs->count() > 0)
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                        Role Change Security Audit Trail
                    </h2>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-[#E5E5E5] text-[#6B6B6B] dark:border-[#262626] dark:text-[#A1A1A1]">
                                    <th class="pb-2 font-bold">Transition</th>
                                    <th class="pb-2 font-bold">Changed By</th>
                                    <th class="pb-2 font-bold text-right">Timestamp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#E5E5E5] dark:divide-[#262626]">
                                @foreach($employee->roleChangeLogs as $log)
                                    <tr>
                                        <td class="py-2.5 font-bold text-[#111111] dark:text-white">
                                            <span class="uppercase font-mono text-[11px] text-slate-500">{{ $log->old_role }}</span>
                                            <span class="text-brand-500 font-bold mx-1.5">→</span>
                                            <span class="uppercase font-mono text-[11px] text-brand-500 font-extrabold">{{ $log->new_role }}</span>
                                        </td>
                                        <td class="py-2.5 text-[#111111] dark:text-white">
                                            {{ $log->changedBy->name ?? 'Administrator' }}
                                        </td>
                                        <td class="py-2.5 text-right text-[#6B6B6B] dark:text-[#A1A1A1]">
                                            {{ $log->created_at->format('d M Y, h:i A') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>

        {{-- Right Column: Role Assignment & Status Controls --}}
        <div class="lg:col-span-5 space-y-6">

            {{-- 1. Role-Based Access Control (Assign Role) --}}
            <div class="rounded-2xl border border-brand-500/30 bg-white p-6 dark:border-brand-500/30 dark:bg-[#141414] shadow-xs">
                <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                            Assign System Role
                        </h2>
                        <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1] mt-0.5">
                            Authorize administrative access portal permissions.
                        </p>
                    </div>
                    <span class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $roleBadge }}">
                        {{ $currentRole }}
                    </span>
                </div>

                <form id="role-assignment-form" action="{{ route('admin.employees.role.update', $employee) }}" method="POST" class="mt-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-[#111111] dark:text-white mb-1.5">
                            Select Organizational Role:
                        </label>
                        <select
                            name="role"
                            id="target-role-select"
                            class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs font-bold text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                        >
                            <option value="employee" {{ $currentRole === 'employee' ? 'selected' : '' }}>Employee (Standard Staff Portal)</option>
                            <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>Admin (Full System & ATS Access)</option>
                            <option value="hr" {{ $currentRole === 'hr' ? 'selected' : '' }}>HR (Human Resources Portal)</option>
                            <option value="tr" {{ $currentRole === 'tr' ? 'selected' : '' }}>TR (Technical Recruiter Portal)</option>
                        </select>
                    </div>

                    <button
                        type="button"
                        onclick="showRoleConfirmModal()"
                        class="mt-4 w-full rounded-xl bg-brand-500 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                    >
                        Update Role →
                    </button>
                </form>
            </div>

            {{-- 2. Status Management Card --}}
            <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                    Update Employee Status
                </h2>
                <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Manage employee onboarding state and physical joining arrival.
                </p>

                <form action="{{ route('admin.employees.status', $employee) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PATCH')

                    <select
                        name="status"
                        class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs font-bold text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                        <option value="pending" {{ $employee->status === 'pending' ? 'selected' : '' }}>Pending (Awaiting Joining Date)</option>
                        <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>Active (Confirmed & Joined)</option>
                        <option value="inactive" {{ $employee->status === 'inactive' ? 'selected' : '' }}>Inactive / Separated</option>
                    </select>

                    <button
                        type="submit"
                        class="mt-3 w-full rounded-xl bg-[#111111] py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
                    >
                        Save Employment Status
                    </button>
                </form>
            </div>

            {{-- 3. Final Accepted Offer Summary --}}
            @if($employee->offer)
                <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
                    <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
                        <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                            Accepted Offer Terms
                        </h2>
                        <span class="rounded-full bg-slate-500/10 px-2 py-0.5 text-[10px] font-bold text-[#6B6B6B] dark:text-[#A1A1A1]">
                            v{{ $employee->offer->version ?? 1 }}
                        </span>
                    </div>

                    <div class="mt-4 space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Annual CTC:</span>
                            <span class="font-extrabold text-[#111111] dark:text-white">₹{{ number_format($employee->offer->salary, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Joining Date:</span>
                            <span class="font-bold text-brand-500">{{ $employee->offer->joining_date->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-[#6B6B6B] dark:text-[#A1A1A1]">Offer Status:</span>
                            <span class="font-bold text-emerald-600 dark:text-emerald-400 capitalize">{{ $employee->offer->status }}</span>
                        </div>
                        @if($employee->offer->offer_letter_path)
                            <div class="pt-2 border-t border-[#E5E5E5] dark:border-[#262626]">
                                <a
                                    href="{{ asset('storage/' . $employee->offer->offer_letter_path) }}"
                                    target="_blank"
                                    class="block w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2 text-center text-xs font-bold text-[#111111] hover:border-brand-500 hover:text-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white transition"
                                >
                                    📄 View Original Offer Letter (v{{ $employee->offer->version ?? 1 }}) ↗
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>

{{-- Role Change Confirmation Modal --}}
<div id="role-confirm-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60 backdrop-blur-xs p-4">
    <div class="w-full max-w-md rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-2xl">
        <div class="flex items-center justify-between border-b border-[#E5E5E5] pb-3 dark:border-[#262626]">
            <h2 class="text-base font-bold text-[#111111] dark:text-white">
                Change Employee Role?
            </h2>
            <button type="button" onclick="hideRoleConfirmModal()" class="text-slate-400 hover:text-slate-600">✕</button>
        </div>

        <div class="mt-4 space-y-3 text-xs text-[#111111] dark:text-white">
            <p class="text-[#6B6B6B] dark:text-[#A1A1A1]">
                You are about to modify system authorization permissions for:
            </p>
            <div class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] p-3.5 dark:border-[#262626] dark:bg-[#1A1A1A] space-y-1.5">
                <p><strong>Employee:</strong> {{ $employee->user->name }} ({{ $employee->employee_code }})</p>
                <p><strong>Current Role:</strong> <span class="font-mono uppercase font-bold text-slate-600 dark:text-slate-400">{{ $currentRole }}</span></p>
                <p><strong>New Role:</strong> <span id="modal-target-role" class="font-mono uppercase font-bold text-brand-500"></span></p>
            </div>
            <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                The user will immediately gain permissions associated with the new role and will be notified in-app.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-2.5 pt-4 mt-4 border-t border-[#E5E5E5] dark:border-[#262626]">
            <button
                type="button"
                onclick="hideRoleConfirmModal()"
                class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] py-2.5 text-xs font-bold text-[#111111] hover:bg-slate-100 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >
                Cancel
            </button>
            <button
                type="button"
                onclick="submitRoleChange()"
                class="rounded-xl bg-brand-500 py-2.5 text-xs font-bold text-white hover:bg-brand-600 transition shadow-xs"
            >
                Confirm Role Change ✓
            </button>
        </div>
    </div>
</div>

<script>
    function showRoleConfirmModal() {
        const select = document.getElementById('target-role-select');
        const targetRole = select.value;
        document.getElementById('modal-target-role').textContent = targetRole.toUpperCase();
        document.getElementById('role-confirm-modal').classList.remove('hidden');
    }

    function hideRoleConfirmModal() {
        document.getElementById('role-confirm-modal').classList.add('hidden');
    }

    function submitRoleChange() {
        document.getElementById('role-assignment-form').submit();
    }
</script>

@endsection
