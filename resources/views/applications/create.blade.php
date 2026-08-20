@extends('layouts.app')

@section('title', 'Apply - ' . $job->title)

@section('content')

<div class="min-h-screen bg-slate-50">

    <div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">

        <a
            href="{{ route('jobs.show', $job) }}"
            class="text-sm font-medium text-slate-500 hover:text-indigo-600"
        >
            ← Back to Job
        </a>


        <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

            <p class="text-sm font-semibold text-indigo-600">
                {{ $job->company }}
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-900">
                Apply for {{ $job->title }}
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Submit your resume and application.
            </p>


            @if($errors->any())

                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4">

                    <ul class="list-disc pl-5 text-sm text-red-600">

                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                action="{{ route('applications.store', $job) }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-8 space-y-6"
            >

                @csrf


                {{-- Existing Resumes --}}

                @if($resumes->count())

                    <div>

                        <label class="text-sm font-semibold text-slate-700">
                            Select Existing Resume
                        </label>

                        <div class="mt-3 space-y-3">

                            @foreach($resumes as $resume)

                                <label class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 p-4 hover:border-indigo-400">

                                    <input
                                        type="radio"
                                        name="resume_id"
                                        value="{{ $resume->id }}"
                                    >

                                    <div>

                                        <p class="text-sm font-semibold text-slate-900">
                                            {{ $resume->file_name }}
                                        </p>

                                        <p class="text-xs text-slate-400">
                                            Uploaded {{ $resume->created_at->diffForHumans() }}
                                        </p>

                                    </div>

                                </label>

                            @endforeach

                        </div>

                    </div>

                @endif


                {{-- Upload Resume --}}

                <div>

                    <label
                        for="resume"
                        class="text-sm font-semibold text-slate-700"
                    >
                        Upload New Resume
                    </label>

                    <input
                        id="resume"
                        name="resume"
                        type="file"
                        accept=".pdf,.doc,.docx"
                        class="mt-2 block w-full rounded-xl border border-slate-200 bg-white p-3 text-sm"
                    >

                    <p class="mt-2 text-xs text-slate-400">
                        PDF, DOC or DOCX — maximum 5MB.
                    </p>

                </div>


                {{-- Cover Letter --}}

                <div>

                    <label
                        for="cover_letter"
                        class="text-sm font-semibold text-slate-700"
                    >
                        Cover Letter
                    </label>

                    <textarea
                        id="cover_letter"
                        name="cover_letter"
                        rows="8"
                        placeholder="Write your cover letter..."
                        class="mt-2 w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    >{{ old('cover_letter') }}</textarea>

                </div>


                {{-- Submit --}}

                <div class="flex justify-end gap-3">

                    <a
                        href="{{ route('jobs.show', $job) }}"
                        class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-semibold text-slate-600 hover:bg-slate-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white hover:bg-indigo-700"
                    >
                        Submit Application
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
