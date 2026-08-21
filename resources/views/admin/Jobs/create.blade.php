@extends('layouts.admin')

@section('title', 'Create Job Opening')
@section('header_title', 'Job Postings')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Breadcrumb Header --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('admin.jobs.index') }}" class="hover:text-brand-500 transition">
            Job Openings
        </a>
        <span>/</span>
        <span class="text-[#111111] dark:text-white font-bold">Create Position</span>
    </div>

    <div>
        <h1 class="text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
            Publish New Job Opening
        </h1>
        <p class="mt-1 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
            Create a detailed job listing with requirements, skills, and application timeline.
        </p>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4">
            <p class="text-xs font-bold text-red-600 dark:text-red-400">Please correct the following errors:</p>
            <ul class="mt-1 list-disc pl-5 text-xs text-red-600 dark:text-red-400 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.jobs.store') }}" class="space-y-6">
        @csrf

        {{-- 1. Basic Information --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                1. Basic Information
            </h2>
            <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Core role attributes and organization details.
            </p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                {{-- Title --}}
                <div class="sm:col-span-2">
                    <label for="title" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Job Title *
                    </label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                        placeholder="e.g. Senior Backend Engineer"
                        required
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Company --}}
                <div>
                    <label for="company" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Company / Division *
                    </label>
                    <input
                        id="company"
                        name="company"
                        type="text"
                        value="{{ old('company') }}"
                        placeholder="e.g. TechCorp Global"
                        required
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Location --}}
                <div>
                    <label for="location" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Location / Work Mode
                    </label>
                    <input
                        id="location"
                        name="location"
                        type="text"
                        value="{{ old('location') }}"
                        placeholder="e.g. Remote / Bangalore"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                {{-- Job Type --}}
                <div>
                    <label for="job_type" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Employment Type
                    </label>
                    <select
                        id="job_type"
                        name="job_type"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                        <option value="">Select employment type</option>
                        <option value="Full Time" {{ old('job_type') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                        <option value="Part Time" {{ old('job_type') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                        <option value="Internship" {{ old('job_type') === 'Internship' ? 'selected' : '' }}>Internship</option>
                        <option value="Contract" {{ old('job_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                </div>

                {{-- Experience --}}
                <div>
                    <label for="experience" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Experience Range
                    </label>
                    <input
                        id="experience"
                        name="experience"
                        type="text"
                        value="{{ old('experience') }}"
                        placeholder="e.g. 2-4 years"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>
            </div>
        </div>

        {{-- 2. Role Description & Skills --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                2. Role Description & Skills
            </h2>
            <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Requirements, responsibilities, and key technologies.
            </p>

            <div class="mt-5 space-y-4">
                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Job Description *
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="7"
                        required
                        placeholder="Describe the team, mission, technical stack, responsibilities, and candidate requirements..."
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-3 text-xs leading-relaxed text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="skills" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Required Skills (Comma separated)
                    </label>
                    <input
                        id="skills"
                        name="skills"
                        type="text"
                        value="{{ old('skills') }}"
                        placeholder="PHP, Laravel, MySQL, Docker, Redis"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs font-mono text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>
            </div>
        </div>

        {{-- 3. Application Timeline --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                3. Application Timeline & URL
            </h2>
            <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Dates during which candidate applications are accepted.
            </p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="apply_url" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        External Application URL (Optional)
                    </label>
                    <input
                        id="apply_url"
                        name="apply_url"
                        type="url"
                        value="{{ old('apply_url') }}"
                        placeholder="https://example.com/careers/apply"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                <div>
                    <label for="application_start" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Applications Open At
                    </label>
                    <input
                        id="application_start"
                        name="application_start"
                        type="datetime-local"
                        value="{{ old('application_start') }}"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                <div>
                    <label for="application_deadline" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Application Deadline
                    </label>
                    <input
                        id="application_deadline"
                        name="application_deadline"
                        type="datetime-local"
                        value="{{ old('application_deadline') }}"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 focus:bg-white dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>
            </div>
        </div>

        {{-- 4. Interview Pipeline Schedule --}}
        <div class="rounded-2xl border border-[#E5E5E5] bg-white p-6 dark:border-[#262626] dark:bg-[#141414] shadow-xs">
            <h2 class="text-sm font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                4. Estimated Interview Milestones
            </h2>
            <p class="mt-0.5 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
                Target schedule for screening, interviews, and final results.
            </p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="screening_date" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Screening Date
                    </label>
                    <input
                        id="screening_date"
                        name="screening_date"
                        type="datetime-local"
                        value="{{ old('screening_date') }}"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                <div>
                    <label for="interview_start" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Interview Rounds Start
                    </label>
                    <input
                        id="interview_start"
                        name="interview_start"
                        type="datetime-local"
                        value="{{ old('interview_start') }}"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                <div>
                    <label for="interview_end" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Interview Rounds End
                    </label>
                    <input
                        id="interview_end"
                        name="interview_end"
                        type="datetime-local"
                        value="{{ old('interview_end') }}"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>

                <div>
                    <label for="result_date" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                        Results Decision Date
                    </label>
                    <input
                        id="result_date"
                        name="result_date"
                        type="datetime-local"
                        value="{{ old('result_date') }}"
                        class="mt-1.5 w-full rounded-xl border border-[#E5E5E5] bg-[#F7F7F7] px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                    >
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a
                href="{{ route('admin.jobs.index') }}"
                class="rounded-xl border border-[#E5E5E5] bg-white px-5 py-2.5 text-xs font-bold text-[#111111] transition hover:bg-[#F7F7F7] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
            >
                Cancel
            </a>
            <button
                type="submit"
                class="rounded-xl bg-brand-500 px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
            >
                Publish Job Opening →
            </button>
        </div>
    </form>

</div>

@endsection