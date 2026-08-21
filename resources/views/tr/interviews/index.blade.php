@extends('layouts.tr')

@section('title', 'Technical Interviews')
@section('header_title', 'Scheduled & Completed Technical Assessments')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold tracking-tight text-[#111111] dark:text-white">
            Technical Interview Rounds
        </h1>
        <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Track upcoming meetings, meeting links, and candidate evaluation notes.
        </p>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
        @php $curr = request('status'); @endphp
        <a href="{{ route('tr.interviews.index') }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ !$curr ? 'bg-blue-600 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            All Rounds ({{ $metrics['total'] }})
        </a>
        <a href="{{ route('tr.interviews.index', ['status' => 'scheduled']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'scheduled' ? 'bg-blue-600 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Scheduled ({{ $metrics['scheduled'] }})
        </a>
        <a href="{{ route('tr.interviews.index', ['status' => 'completed']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'completed' ? 'bg-emerald-600 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Completed ({{ $metrics['completed'] }})
        </a>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        @if($interviews->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                        <tr>
                            <th class="px-6 py-3.5">Candidate</th>
                            <th class="px-6 py-3.5">Job Role</th>
                            <th class="px-6 py-3.5">Interview Schedule</th>
                            <th class="px-6 py-3.5">Status</th>
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
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $interview->interview_date->format('d M Y') }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        {{ \Carbon\Carbon::parse($interview->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($interview->end_time)->format('h:i A') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase {{ $interview->status === 'completed' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-blue-500/10 text-blue-600' }}">
                                        {{ $interview->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('tr.applications.show', $interview->application) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition"
                                    >
                                        <span>Dossier</span>
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
                No interviews found.
            </div>
        @endif
    </div>

</div>

@endsection
