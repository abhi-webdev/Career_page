@extends('layouts.app')

@section('title', 'Manage Jobs')

@section('content')

<div class="min-h-screen bg-slate-50">

    {{-- Header --}}

    <div class="border-b border-slate-200 bg-white">

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <div class="flex items-center gap-2 text-sm text-slate-500">

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="hover:text-indigo-600"
                        >
                            Admin
                        </a>

                        <span>/</span>

                        <span>Jobs</span>

                    </div>

                    <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                        Manage Jobs
                    </h1>

                    <p class="mt-1 text-sm text-slate-500">
                        Create, edit and manage job opportunities.
                    </p>

                </div>


                <a
                    href="{{ route('admin.jobs.create') }}"
                    class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-indigo-700"
                >
                    <span class="mr-2 text-lg">+</span>
                    Create Job
                </a>

            </div>

        </div>

    </div>


    {{-- Content --}}

    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">


        {{-- Success Message --}}

        @if(session('success'))

            <div class="mb-6 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">

                <span class="font-bold">✓</span>

                {{ session('success') }}

            </div>

        @endif


        {{-- Stats --}}

        <div class="mb-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <p class="text-sm font-medium text-slate-500">
                    Total Jobs
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $jobs->total() }}
                </p>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <p class="text-sm font-medium text-slate-500">
                    Current Page
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">
                    {{ $jobs->currentPage() }}
                </p>

            </div>


            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:col-span-2 lg:col-span-1">

                <p class="text-sm font-medium text-slate-500">
                    Showing
                </p>

                <p class="mt-2 text-3xl font-bold text-slate-900">

                    {{ $jobs->count() }}

                </p>

            </div>

        </div>


        {{-- Jobs --}}

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Table Header --}}

            <div class="border-b border-slate-200 px-6 py-5">

                <h2 class="text-lg font-semibold text-slate-900">
                    All Jobs
                </h2>

            </div>


            @if($jobs->count())

                {{-- Desktop Table --}}

                <div class="hidden overflow-x-auto md:block">

                    <table class="w-full">

                        <thead class="bg-slate-50">

                            <tr>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Job
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Company
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Location
                                </th>

                                <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Type
                                </th>

                                <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                    Actions
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100">

                            @foreach($jobs as $job)

                                <tr class="transition hover:bg-slate-50">

                                    <td class="px-6 py-5">

                                        <div>

                                            <p class="font-semibold text-slate-900">
                                                {{ $job->title }}
                                            </p>

                                            <p class="mt-1 text-xs text-slate-400">
                                                Posted {{ $job->created_at->diffForHumans() }}
                                            </p>

                                        </div>

                                    </td>


                                    <td class="px-6 py-5">

                                        <span class="text-sm text-slate-600">
                                            {{ $job->company }}
                                        </span>

                                    </td>


                                    <td class="px-6 py-5">

                                        <span class="text-sm text-slate-600">

                                            {{ $job->location ?? 'Remote' }}

                                        </span>

                                    </td>


                                    <td class="px-6 py-5">

                                        <span class="inline-flex rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">

                                            {{ $job->job_type ?? 'N/A' }}

                                        </span>

                                    </td>


                                    <td class="px-6 py-5">

                                        <div class="flex justify-end gap-2">

                                            <a
                                                href="{{ route('jobs.show', $job) }}"
                                                class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-100"
                                            >
                                                View
                                            </a>


                                            <a
                                                href="{{ route('admin.jobs.edit', $job) }}"
                                                class="rounded-lg border border-indigo-200 bg-indigo-50 px-3 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100"
                                            >
                                                Edit
                                            </a>


                                            <form
                                                method="POST"
                                                action="{{ route('admin.jobs.destroy', $job) }}"
                                                onsubmit="return confirm('Are you sure you want to delete this job?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-100"
                                                >
                                                    Delete
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                {{-- Mobile Cards --}}

                <div class="divide-y divide-slate-100 md:hidden">

                    @foreach($jobs as $job)

                        <div class="p-5">

                            <div class="flex items-start justify-between gap-4">

                                <div>

                                    <h3 class="font-semibold text-slate-900">
                                        {{ $job->title }}
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $job->company }}
                                    </p>

                                </div>


                                <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">

                                    {{ $job->job_type ?? 'N/A' }}

                                </span>

                            </div>


                            <div class="mt-4 space-y-1 text-sm text-slate-500">

                                <p>
                                    📍 {{ $job->location ?? 'Remote' }}
                                </p>

                                @if($job->experience)

                                    <p>
                                        💼 {{ $job->experience }}
                                    </p>

                                @endif

                            </div>


                            <div class="mt-5 flex gap-2">

                                <a
                                    href="{{ route('jobs.show', $job) }}"
                                    class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-center text-xs font-semibold text-slate-600"
                                >
                                    View
                                </a>

                                <a
                                    href="{{ route('admin.jobs.edit', $job) }}"
                                    class="flex-1 rounded-lg bg-indigo-50 px-3 py-2 text-center text-xs font-semibold text-indigo-600"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    action="{{ route('admin.jobs.destroy', $job) }}"
                                    class="flex-1"
                                    onsubmit="return confirm('Are you sure you want to delete this job?');"
                                >

                                    @csrf

                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="w-full rounded-lg bg-red-50 px-3 py-2 text-xs font-semibold text-red-600"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>


            @else

                <div class="px-6 py-16 text-center">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-2xl">
                        📋
                    </div>

                    <h3 class="mt-4 text-lg font-semibold text-slate-900">
                        No jobs yet
                    </h3>

                    <p class="mt-2 text-sm text-slate-500">
                        Create your first job opportunity.
                    </p>

                    <a
                        href="{{ route('admin.jobs.create') }}"
                        class="mt-6 inline-flex rounded-xl bg-indigo-600 px-5 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Create Job
                    </a>

                </div>

            @endif


            {{-- Pagination --}}

            @if($jobs->hasPages())

                <div class="border-t border-slate-200 px-6 py-5">

                    {{ $jobs->links() }}

                </div>

            @endif

        </div>

    </div>

</div>

@endsection