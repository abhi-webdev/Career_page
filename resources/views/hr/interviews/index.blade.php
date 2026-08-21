@extends('layouts.hr')

@section('title', 'My Assigned HR Interviews')
@section('header_title', 'My Interviews')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-xl font-bold tracking-tight text-[#111111] dark:text-white">
                My Interviews
            </h1>
            <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                HR interviews assigned to you. Conduct screening, join meetings, and submit recommendations.
            </p>
        </div>
    </div>

    {{-- Filter Tabs (All Assigned, Today, Upcoming, Completed, Cancelled) --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
        @php $curr = $filter ?? request('filter', request('status', 'all')); @endphp
        <a href="{{ route('hr.interviews.index', ['filter' => 'all']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'all' ? 'bg-purple-600 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            All Assigned ({{ $metrics['total'] }})
        </a>
        <a href="{{ route('hr.interviews.index', ['filter' => 'today']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'today' ? 'bg-purple-600 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Today ({{ $metrics['today'] }})
        </a>
        <a href="{{ route('hr.interviews.index', ['filter' => 'upcoming']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'upcoming' ? 'bg-purple-600 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Upcoming ({{ $metrics['upcoming'] }})
        </a>
        <a href="{{ route('hr.interviews.index', ['filter' => 'completed']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Completed ({{ $metrics['completed'] }})
        </a>
        <a href="{{ route('hr.interviews.index', ['filter' => 'cancelled']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'cancelled' ? 'bg-red-600 text-white shadow-xs' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Cancelled ({{ $metrics['cancelled'] }})
        </a>
    </div>

    {{-- Table / Cards --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        @if($interviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                        <tr>
                            <th class="px-6 py-3.5">Candidate</th>
                            <th class="px-6 py-3.5">Position</th>
                            <th class="px-6 py-3.5">Interview Schedule</th>
                            <th class="px-6 py-3.5">Meeting Link</th>
                            <th class="px-6 py-3.5">Status & Result</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5] font-medium dark:divide-[#262626]">
                        @foreach($interviews as $interview)
                            <tr class="transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $interview->application->user->name }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $interview->application->user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold">{{ $interview->application->job->title }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $interview->application->job->company }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5">
                                        @if($interview->interview_date->isToday())
                                            <span class="rounded-md bg-purple-600 px-1.5 py-0.5 text-[9px] font-bold text-white uppercase">Today</span>
                                        @endif
                                        <p class="font-bold text-[#111111] dark:text-white">{{ $interview->interview_date->format('d M Y') }}</p>
                                    </div>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($interview->meeting_link)
                                        <a
                                            href="{{ $interview->meeting_link }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-flex items-center gap-1 text-xs font-bold text-purple-600 hover:underline"
                                        >
                                            <span>📹 Join Meet</span>
                                            <span>↗</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($interview->status === 'completed')
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $interview->result === 'passed' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-red-500/10 text-red-600' }}">
                                            ✓ {{ $interview->result ?? 'Completed' }}
                                        </span>
                                    @elseif($interview->status === 'cancelled')
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-red-500/10 text-red-600">
                                            Cancelled
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase bg-purple-500/10 text-purple-600">
                                            {{ $interview->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('hr.interviews.show', $interview) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-purple-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-purple-700 transition"
                                    >
                                        <span>View Interview</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
                {{ $interviews->links() }}
            </div>
        @else
            <div class="py-16 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                No HR interviews found in this view.
            </div>
        @endif
    </div>

</div>

@endsection
