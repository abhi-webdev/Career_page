@extends('layouts.admin')

@section('title', 'Employee Directory')
@section('header_title', 'Hired Employees & Onboarding')

@section('content')

<div class="space-y-8 max-w-7xl mx-auto">

    {{-- Top Header Banner --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                Staff Management
            </span>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                Employee Directory
            </h1>
            <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Official record of hired candidates with signed employment offers and scheduled joining dates.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a
                href="{{ route('admin.applications.index') }}"
                class="inline-flex items-center gap-1.5 rounded-xl border border-[#E5E5E5] bg-white px-4 py-2.5 text-xs font-bold text-[#111111] shadow-xs transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
            >
                <span>👥</span>
                <span>Candidate Pipeline</span>
            </a>
        </div>
    </div>

    {{-- 4 Stat Metric Pills --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Total --}}
        <a
            href="{{ route('admin.employees.index') }}"
            class="rounded-2xl border p-5 transition shadow-xs {{ !request('status') ? 'border-brand-500 bg-brand-500/5 dark:bg-brand-500/10' : 'border-[#E5E5E5] bg-white dark:border-[#262626] dark:bg-[#141414]' }}"
        >
            <span class="text-[11px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:text-[#A1A1A1]">Total Employees</span>
            <p class="mt-2 text-2xl font-extrabold text-[#111111] dark:text-white">{{ $metrics['total'] }}</p>
            <p class="mt-1 text-[11px] text-brand-500 font-semibold">All hired talent</p>
        </a>

        {{-- Pending --}}
        <a
            href="{{ route('admin.employees.index', ['status' => 'pending']) }}"
            class="rounded-2xl border p-5 transition shadow-xs {{ request('status') === 'pending' ? 'border-amber-500 bg-amber-500/5 dark:bg-amber-500/10' : 'border-[#E5E5E5] bg-white dark:border-[#262626] dark:bg-[#141414]' }}"
        >
            <span class="text-[11px] font-bold uppercase tracking-wider text-amber-600 dark:text-amber-400">Pending Arrival</span>
            <p class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $metrics['pending'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Awaiting joining date</p>
        </a>

        {{-- Active --}}
        <a
            href="{{ route('admin.employees.index', ['status' => 'active']) }}"
            class="rounded-2xl border p-5 transition shadow-xs {{ request('status') === 'active' ? 'border-emerald-500 bg-emerald-500/5 dark:bg-emerald-500/10' : 'border-[#E5E5E5] bg-white dark:border-[#262626] dark:bg-[#141414]' }}"
        >
            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">Active Staff</span>
            <p class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $metrics['active'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Confirmed & joined</p>
        </a>

        {{-- Inactive --}}
        <a
            href="{{ route('admin.employees.index', ['status' => 'inactive']) }}"
            class="rounded-2xl border p-5 transition shadow-xs {{ request('status') === 'inactive' ? 'border-red-500 bg-red-500/5 dark:bg-red-500/10' : 'border-[#E5E5E5] bg-white dark:border-[#262626] dark:bg-[#141414]' }}"
        >
            <span class="text-[11px] font-bold uppercase tracking-wider text-red-600 dark:text-red-400">Inactive</span>
            <p class="mt-2 text-2xl font-extrabold text-red-600 dark:text-red-400">{{ $metrics['inactive'] }}</p>
            <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">Archived</p>
        </a>
    </div>

    {{-- Search & Filter Bar --}}
    <div class="rounded-2xl border border-[#E5E5E5] bg-white p-4 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="relative flex-1 w-full">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by Employee ID, Candidate Name, Email, or Job Position..."
                    class="w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
            </div>

            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button
                    type="submit"
                    class="w-full sm:w-auto rounded-xl bg-brand-500 px-5 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600"
                >
                    Search
                </button>
                @if(request('search') || request('status'))
                    <a
                        href="{{ route('admin.employees.index') }}"
                        class="rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs font-bold text-[#6B6B6B] hover:text-[#111111] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]"
                    >
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Employees Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        @if($employees->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1] border-b border-[#E5E5E5] dark:border-[#262626]">
                        <tr>
                            <th class="px-6 py-4">Employee ID</th>
                            <th class="px-6 py-4">Candidate</th>
                            <th class="px-6 py-4">Job Position</th>
                            <th class="px-6 py-4">Company</th>
                            <th class="px-6 py-4">Joining Date</th>
                            <th class="px-6 py-4">Offer Status</th>
                            <th class="px-6 py-4">Signed Offer</th>
                            <th class="px-6 py-4">Employee Status</th>
                            <th class="px-6 py-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5] font-medium dark:divide-[#262626]">
                        @foreach($employees as $emp)
                            @php
                                $empStatus = strtolower($emp->status);
                                $statusBadge = match($empStatus) {
                                    'pending' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                                    'active' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                                    'inactive' => 'bg-red-500/10 text-red-600 dark:text-red-400 border-red-500/20',
                                    default => 'bg-slate-500/10 text-slate-500 border-slate-500/20',
                                };
                            @endphp
                            <tr class="transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                {{-- Employee ID --}}
                                <td class="px-6 py-4">
                                    <span class="font-extrabold text-brand-500 font-mono text-xs">
                                        {{ $emp->employee_code }}
                                    </span>
                                </td>

                                {{-- Candidate Name & Email --}}
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $emp->user->name }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $emp->user->email }}</p>
                                </td>

                                {{-- Position --}}
                                <td class="px-6 py-4 font-semibold">
                                    {{ $emp->application->job->title }}
                                </td>

                                {{-- Company --}}
                                <td class="px-6 py-4 text-[#6B6B6B] dark:text-[#A1A1A1]">
                                    {{ $emp->application->job->company }}
                                </td>

                                {{-- Joining Date --}}
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $emp->joining_date->format('d M Y') }}</p>
                                    <p class="text-[10px] text-brand-500">{{ $emp->joining_date->diffForHumans() }}</p>
                                </td>

                                {{-- Offer Status --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        Accepted
                                    </span>
                                </td>

                                {{-- Signed Offer Document --}}
                                <td class="px-6 py-4">
                                    @if($emp->offer && $emp->offer->signed_offer_letter_path)
                                        <a
                                            href="{{ route('admin.employees.signed-offer', $emp) }}"
                                            class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline"
                                        >
                                            <span>✓ Signed PDF</span>
                                            <span>📥</span>
                                        </a>
                                    @else
                                        <span class="text-[11px] text-slate-400">N/A</span>
                                    @endif
                                </td>

                                {{-- Employee Status --}}
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[10px] font-bold capitalize {{ $statusBadge }}">
                                        {{ $emp->status }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('admin.employees.show', $emp) }}"
                                        class="inline-flex items-center gap-1 rounded-xl bg-[#111111] px-3 py-1.5 text-xs font-bold text-white transition hover:bg-brand-500 dark:bg-white dark:text-[#111111] dark:hover:bg-brand-500 dark:hover:text-white"
                                    >
                                        <span>View</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="p-4 border-t border-[#E5E5E5] dark:border-[#262626]">
                {{ $employees->links() }}
            </div>
        @else
            <div class="p-16 text-center">
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-500/10 text-3xl text-brand-500">
                    👔
                </span>
                <h3 class="mt-4 text-base font-bold text-[#111111] dark:text-white">
                    No employee records found
                </h3>
                <p class="mt-1.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1] max-w-sm mx-auto">
                    When candidates accept their official employment offer with an uploaded signed document, their employee profile will automatically be created here.
                </p>
                <a
                    href="{{ route('admin.applications.index') }}"
                    class="mt-5 inline-flex items-center gap-1 rounded-xl bg-brand-500 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-600"
                >
                    Browse Active Applications →
                </a>
            </div>
        @endif
    </div>

</div>

@endsection
