@extends('layouts.app')

@section('title', 'Update Job')

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="border-b border-slate-200 bg-white">

        <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

            <div class="flex items-center gap-2 text-sm text-slate-500">

                <a
                    href="{{ route('admin.jobs.index') }}"
                    class="hover:text-indigo-600"
                >
                    Manage Jobs
                </a>

                <span>/</span>

                <span>Update Job</span>

            </div>

            <h1 class="mt-3 text-3xl font-bold text-slate-900">
                Update {{ $job->title }} Job
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Update the details of the job opportunity.
            </p>

        </div>

    </div>


    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

        @if($errors->any())

            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">

                <p class="font-semibold text-red-700">
                    Please fix the following errors:
                </p>

                <ul class="mt-2 list-disc pl-5 text-sm text-red-600">

                    @foreach($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('admin.jobs.update', $job) }}"
            class="space-y-6"
        >

            @csrf

            @method('PUT')
            {{-- Basic Information --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-900">
                    Basic Information
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Provide the main details about the position.
                </p>


                <div class="mt-6 grid gap-6 md:grid-cols-2">

                    <div class="md:col-span-2">

                        <label
                            for="title"
                            class="text-sm font-medium text-slate-700"
                        >
                            Job Title
                        </label>

                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title', $job->title) }}"
                            placeholder="e.g. Backend Developer"
                            required
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>


                    <div>

                        <label
                            for="company"
                            class="text-sm font-medium text-slate-700"
                        >
                            Company
                        </label>

                        <input
                            id="company"
                            name="company"
                            type="text"
                            value="{{ old('company', $job->company) }}"
                            placeholder="Company name"
                            required
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>


                    <div>

                        <label
                            for="location"
                            class="text-sm font-medium text-slate-700"
                        >
                            Location
                        </label>

                        <input
                            id="location"
                            name="location"
                            type="text"
                            value="{{ old('location', $job->location) }}"
                            placeholder="e.g. Bhopal"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>


                    <div>

                        <label
                            for="job_type"
                            class="text-sm font-medium text-slate-700"
                        >
                            Job Type
                        </label>

                        <select
                            id="job_type"
                            name="job_type"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                            <option value="">
                                Select job type
                            </option>

                            <option value="Full Time">
                                Full Time
                            </option>

                            <option value="Part Time">
                                Part Time
                            </option>

                            <option value="Internship">
                                Internship
                            </option>

                            <option value="Contract">
                                Contract
                            </option>

                        </select>

                    </div>


                    <div>

                        <label
                            for="experience"
                            class="text-sm font-medium text-slate-700"
                        >
                            Experience
                        </label>

                        <input
                            id="experience"
                            name="experience"
                            type="text"
                            value="{{ old('experience', $job->experience) }}"
                            placeholder="e.g. 1-3 years"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>

                </div>

            </div>


            {{-- Description --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-900">
                    Job Description
                </h2>

                <textarea
                    name="description"
                    rows="8"
                    placeholder="Describe the role, responsibilities and requirements..."
                    required
                    class="mt-5 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >{{ old('description', $job->description) }}</textarea>

            </div>


            {{-- Skills --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-900">
                    Skills
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Separate skills using commas.
                </p>

                <input
                    name="skills"
                    type="text"
                    value="{{ old('skills', is_array($job->skills) ? implode(', ', $job->skills) : $job->skills) }}"
                    placeholder="PHP, Laravel, MySQL, Git"
                    class="mt-5 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                >

            </div>


            {{-- Application Details --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-900">
                    Application Details
                </h2>

                <div class="mt-6 space-y-5">

                    <div>

                        <label
                            for="apply_url"
                            class="text-sm font-medium text-slate-700"
                        >
                            Application URL
                        </label>

                        <input
                            id="apply_url"
                            name="apply_url"
                            type="url"
                            value="{{ old('apply_url', $job->apply_url) }}"
                            placeholder="https://example.com/apply"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>


                    <div class="grid gap-5 md:grid-cols-2">

                        <div>

                            <label
                                for="application_start"
                                class="text-sm font-medium text-slate-700"
                            >
                                Application Start
                            </label>

                            <input
                                id="application_start"
                                name="application_start"
                                type="datetime-local"
                                value="{{ old('application_start', $job->application_start) }}"
                                class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            >

                        </div>


                        <div>

                            <label
                                for="application_deadline"
                                class="text-sm font-medium text-slate-700"
                            >
                                Application Deadline
                            </label>

                            <input
                                id="application_deadline"
                                name="application_deadline"
                                type="datetime-local"
                                value="{{ old('application_deadline', $job->application_deadline) }}"
                                class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                            >

                        </div>

                    </div>

                </div>

            </div>


            {{-- Interview Schedule --}}

            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">

                <h2 class="text-lg font-semibold text-slate-900">
                    Interview Schedule
                </h2>

                <div class="mt-6 grid gap-5 md:grid-cols-2">

                    <div>

                        <label
                            for="screening_date"
                            class="text-sm font-medium text-slate-700"
                        >
                            Screening Date
                        </label>

                        <input
                            id="screening_date"
                            name="screening_date"
                            type="datetime-local"
                            value="{{ old('screening_date', $job->screening_date) }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>


                    <div>

                        <label
                            for="interview_start"
                            class="text-sm font-medium text-slate-700"
                        >
                            Interview Start
                        </label>

                        <input
                            id="interview_start"
                            name="interview_start"
                            type="datetime-local"
                            value="{{ old('interview_start', $job->interview_start) }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>


                    <div>

                        <label
                            for="interview_end"
                            class="text-sm font-medium text-slate-700"
                        >
                            Interview End
                        </label>

                        <input
                            id="interview_end"
                            name="interview_end"
                            type="datetime-local"
                            value="{{ old('interview_end', $job->interview_end) }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>


                    <div>

                        <label
                            for="result_date"
                            class="text-sm font-medium text-slate-700"
                        >
                            Result Date
                        </label>

                        <input
                            id="result_date"
                            name="result_date"
                            type="datetime-local"
                            value="{{ old('result_date', $job->result_date) }}"
                            class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                        >

                    </div>

                </div>

            </div>


            {{-- Actions --}}

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.jobs.index') }}"
                    class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700"
                >
                    Update Job
                </button>

            </div>

        </form>

    </div>

</div>

@endsection