@extends('layouts.tr')

@section('title', 'Candidate Pipeline')
@section('header_title', 'Engineering Candidates & Technical Screening')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-xl font-bold tracking-tight text-[#111111] dark:text-white">
            Engineering Candidate Pool
        </h1>
        <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Screen candidates, inspect resumes, schedule interviews, and submit technical evaluations.
        </p>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-[#E5E5E5] pb-4 dark:border-[#262626]">
        @php $curr = request('status'); @endphp
        <a href="{{ route('tr.applications.index') }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ !$curr ? 'bg-blue-600 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            All ({{ $stageCounts['total'] }})
        </a>
        <a href="{{ route('tr.applications.index', ['status' => 'pending']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'pending' ? 'bg-amber-500 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Pending ({{ $stageCounts['pending'] }})
        </a>
        <a href="{{ route('tr.applications.index', ['status' => 'shortlisted']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'shortlisted' ? 'bg-blue-600 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Shortlisted ({{ $stageCounts['shortlisted'] }})
        </a>
        <a href="{{ route('tr.applications.index', ['status' => 'interview']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'interview' ? 'bg-brand-500 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Interview Round ({{ $stageCounts['interview'] }})
        </a>
        <a href="{{ route('tr.applications.index', ['status' => 'selected']) }}" class="rounded-xl px-3.5 py-1.5 text-xs font-bold transition {{ $curr === 'selected' ? 'bg-emerald-600 text-white' : 'border border-[#E5E5E5] bg-white text-[#6B6B6B] dark:border-[#262626] dark:bg-[#141414] dark:text-[#A1A1A1]' }}">
            Selected ({{ $stageCounts['selected'] }})
        </a>
    </div>

    {{-- Applications Table --}}
    <div class="overflow-hidden rounded-2xl border border-[#E5E5E5] bg-white shadow-xs dark:border-[#262626] dark:bg-[#141414]">
        @if($applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-[#111111] dark:text-white">
                    <thead class="bg-[#F7F7F7] text-[10px] font-bold uppercase tracking-wider text-[#6B6B6B] dark:bg-[#1A1A1A] dark:text-[#A1A1A1]">
                        <tr>
                            <th class="px-6 py-3.5">Candidate</th>
                            <th class="px-6 py-3.5">Target Role</th>
                            <th class="px-6 py-3.5">Resume</th>
                            <th class="px-6 py-3.5">Stage</th>
                            <th class="px-6 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E5E5E5] font-medium dark:divide-[#262626]">
                        @foreach($applications as $app)
                            <tr class="transition hover:bg-[#F7F7F7] dark:hover:bg-[#1A1A1A]">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-[#111111] dark:text-white">{{ $app->user->name }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $app->user->email }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-semibold">{{ $app->job->title }}</p>
                                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">{{ $app->job->company }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if($app->resume)
                                        <a
                                            href="{{ asset('storage/' . $app->resume->file_path) }}"
                                            target="_blank"
                                            class="inline-flex items-center gap-1 rounded-lg bg-red-500/10 px-2.5 py-1 text-[11px] font-bold text-red-500 hover:bg-red-500/20"
                                        >
                                            <span>PDF Resume ↗</span>
                                        </a>
                                    @else
                                        <span class="text-slate-400">None</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex rounded-full border px-2.5 py-0.5 text-[11px] font-bold capitalize bg-blue-500/10 text-blue-600 border-blue-500/20">
                                        {{ $app->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a
                                        href="{{ route('tr.applications.show', $app) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-bold text-white shadow-xs hover:bg-blue-700 transition"
                                    >
                                        <span>Evaluate</span>
                                        <span>→</span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-[#E5E5E5] px-6 py-4 dark:border-[#262626]">
                {{ $applications->links() }}
            </div>
        @else
            <div class="py-16 text-center text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                No applications in this filter.
            </div>
        @endif
    </div>

</div>

@endsection
