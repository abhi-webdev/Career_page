@extends('layouts.hr')

@section('title', 'Staff Directory')
@section('header_title', 'Employee Directory & Onboarding Records')

@section('content')

<div class="space-y-6">

    {{-- Top Action & Search Bar --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-[#111111] dark:text-white">
                Hired Staff Members
            </h1>
            <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                View onboarding progress, joining schedules, and signed offer letters.
            </p>
        </div>
    </div>

    {{-- Status Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
        @php
            $currentStatus = request('status');
        @endphp

        <a
            href="{{ route('hr.employees.index') }}"
            class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ !$currentStatus ? 'bg-purple-600 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] hover:border-purple-500 hover:text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1] dark:hover:text-white' }}"
        >
            All Staff ({{ $metrics['total'] }})
        </a>

        <a
            href="{{ route('hr.employees.index', ['status' => 'pending']) }}"
            class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $currentStatus === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] hover:border-amber-500 hover:text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1] dark:hover:text-white' }}"
        >
            Pending Arrival ({{ $metrics['pending'] }})
        </a>

        <a
            href="{{ route('hr.employees.index', ['status' => 'active']) }}"
            class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $currentStatus === 'active' ? 'bg-emerald-600 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] hover:border-emerald-500 hover:text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1] dark:hover:text-white' }}"
        >
            Active Staff ({{ $metrics['active'] }})
        </a>

        <a
            href="{{ route('hr.employees.index', ['status' => 'inactive']) }}"
            class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $currentStatus === 'inactive' ? 'bg-red-500 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] hover:border-red-500 hover:text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1] dark:hover:text-white' }}"
        >
            Inactive ({{ $metrics['inactive'] }})
        </a>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('hr.employees.index') }}" class="flex gap-2">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search by Employee ID, Name, Email, or Job title..."
            class="w-full max-w-md rounded-xl border border-[#E5E5E5] bg-white px-4 py-2 text-xs text-[#111111] outline-none transition focus:border-purple-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
        >
        <button
            type="submit"
            class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-bold text-white shadow-xs hover:bg-purple-700 transition"
        >
            Search
        </button>
        @if(request('search'))
            <a
                href="{{ route('hr.employees.index', request()->only('status')) }}"
                class="rounded-xl border border-[#E5E5E5] bg-white px-3 py-2 text-xs font-bold text-[#6B6B6B] hover:text-[#111111] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]"
            >
                Clear
            </a>
        @endif
    </form>

    {{-- Table Container --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        @if($employees->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                        <tr>
                            <th class="px-6 py-3.5">Employee ID</th>
                            <th class="px-6 py-3.5">Candidate / Staff</th>
                            <th class="px-6 py-3.5">Job Position</th>
                            <th class="px-6 py-3.5">Joining Date</th>
                            <th class="px-6 py-3.5">Signed Offer</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5] font-medium dark:divide-[#262626]">
                        @foreach($employees as $employee)
                            <tr class="transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                <td class="px-6 py-4 font-mono font-bold text-purple-600 dark:text-purple-400">
                                    {{ $employee->employee_code }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $employee->user->name }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $employee->user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-[#111111] dark:text-white">{{ $employee->application->job->title }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $employee->application->job->company }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-bold text-[#111111] dark:text-white">{{ $employee->joining_date->format('d M Y') }}</span>
                                    <p class="text-[10px] text-purple-600 dark:text-purple-400 mt-0.5">{{ $employee->joining_date->diffForHumans() }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($employee->offer && $employee->offer->signed_offer_letter_path)
                                        <a
                                            href="{{ route('hr.employees.signed-offer', $employee) }}"
                                            class="inline-flex items-center gap-1 rounded-lg bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500/20"
                                        >
                                            <span>✓ Signed PDF</span>
                                            <span>📥</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400 text-[11px]">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $empStatus = strtolower($employee->status);
                                        $badgeClass = match($empStatus) {
                                            'pending' => 'bg-amber-500/10 text-amber-600 border-amber-500/20',
                                            'active' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20',
                                            'inactive' => 'bg-red-500/10 text-red-600 border-red-500/20',
                                            default => 'bg-slate-500/10 text-slate-600 border-slate-500/20',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[11px] font-bold capitalize {{ $badgeClass }}">
                                        {{ $employee->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('hr.employees.show', $employee) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-purple-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-purple-700 transition"
                                    >
                                        <span>View Profile</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
                {{ $employees->links() }}
            </div>
        @else
            <div class="py-16 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                No staff members found matching this filter.
            </div>
        @endif
    </div>

</div>

@endsection
