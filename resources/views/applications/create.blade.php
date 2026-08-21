@extends('layouts.app')

@section('title', 'Apply for ' . $job->title)

@section('content')

<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">

    {{-- Breadcrumb Back Link --}}
    <a
        href="{{ route('jobs.show', $job) }}"
        class="inline-flex items-center gap-2 text-xs font-semibold text-[#6B6B6B] transition hover:text-brand-500 dark:text-[#A1A1A1] dark:hover:text-brand-500"
    >
        <span>←</span>
        <span>Back to {{ $job->title }}</span>
    </a>

    {{-- Application Box --}}
    <div class="mt-6 rounded-2xl border border-[#E5E5E5] bg-[#F7F7F7] p-6 sm:p-8 dark:border-[#262626] dark:bg-[#141414]">
        
        <div class="border-b border-[#E5E5E5] pb-6 dark:border-[#262626]">
            <span class="text-xs font-bold uppercase tracking-wider text-brand-500">
                Application Form
            </span>
            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-[#111111] sm:text-3xl dark:text-white">
                Apply for {{ $job->title }}
            </h1>
            <p class="mt-1 text-sm text-[#6B6B6B] dark:text-[#A1A1A1]">
                {{ $job->company }} • {{ $job->location ?? 'Remote' }} • {{ $job->job_type ?? 'Full Time' }}
            </p>
        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mt-6 rounded-xl border border-red-500/20 bg-red-500/10 p-4">
                <ul class="list-disc pl-5 text-xs font-semibold text-red-600 dark:text-red-400 space-y-1">
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
            class="mt-6 space-y-6"
        >
            @csrf

            {{-- Guest Candidate Identity Information --}}
            @guest
                <div class="rounded-2xl border border-brand-500/30 bg-orange-500/5 p-5 dark:border-brand-500/30 dark:bg-[#1A1A1A] space-y-4">
                    <div class="flex items-center justify-between border-b border-brand-500/20 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-brand-500 text-xs font-bold text-white">👤</span>
                            <h2 class="text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white">
                                Candidate Contact Information
                            </h2>
                        </div>
                        <a href="{{ route('login') }}" class="text-xs font-bold text-brand-500 hover:underline">
                            Already registered? Log in →
                        </a>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-1.5">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                placeholder="e.g. Alex Johnson"
                                class="w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                            >
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-1.5">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="e.g. alex@example.com"
                                class="w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-2.5 text-xs text-[#111111] outline-none transition focus:border-brand-500 dark:border-[#262626] dark:bg-[#141414] dark:text-white"
                            >
                        </div>
                    </div>

                    <p class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                        ✨ First-time applicant? A candidate account will be automatically generated and your temporary access password will be sent to your email.
                    </p>
                </div>
            @endguest

            {{-- 1. Select Existing Resume (if any) --}}
            @if($resumes->count())
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-3">
                        Option 1: Select a Saved Resume
                    </label>

                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach($resumes as $resume)
                            <label class="relative flex cursor-pointer items-center gap-3 rounded-xl border border-[#E5E5E5] bg-white p-4 transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A]">
                                <input
                                    type="radio"
                                    name="resume_id"
                                    value="{{ $resume->id }}"
                                    class="accent-brand-500"
                                    onchange="document.getElementById('new-resume-input').value = ''; document.getElementById('selected-file-name').textContent = '';"
                                >
                                <div class="truncate">
                                    <p class="truncate text-xs font-bold text-[#111111] dark:text-white">
                                        📄 {{ $resume->file_name }}
                                    </p>
                                    <p class="text-[10px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                                        Uploaded {{ $resume->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- 2. Upload New Resume --}}
            <div>
                <label for="resume" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-2">
                    {{ $resumes->count() ? 'Option 2: Upload New Resume' : 'Upload Resume' }} <span class="text-red-500">*</span>
                </label>

                <div class="relative rounded-2xl border-2 border-dashed border-[#E5E5E5] bg-white p-6 text-center transition hover:border-brand-500 dark:border-[#262626] dark:bg-[#1A1A1A]">
                    <span class="text-3xl">📤</span>
                    <p class="mt-2 text-xs font-bold text-[#111111] dark:text-white">
                        Click or drag PDF/DOCX to upload
                    </p>
                    <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                        PDF, DOC, DOCX up to 5MB
                    </p>
                    <p id="selected-file-name" class="mt-2 text-xs font-bold text-brand-500"></p>

                    <input
                        id="new-resume-input"
                        name="resume"
                        type="file"
                        accept=".pdf,.doc,.docx"
                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                        onchange="if(this.files[0]) { document.getElementById('selected-file-name').textContent = 'Selected: ' + this.files[0].name + ' (' + (this.files[0].size/1024).toFixed(1) + ' KB)'; const radios = document.getElementsByName('resume_id'); for(let r of radios) r.checked = false; }"
                    >
                </div>
            </div>

            {{-- 3. Cover Letter / Pitch --}}
            <div>
                <label for="cover_letter" class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-2">
                    Cover Letter / Quick Note (Optional)
                </label>
                <textarea
                    id="cover_letter"
                    name="cover_letter"
                    rows="5"
                    placeholder="Introduce yourself, highlight relevant projects, engineering accomplishments, or portfolio links..."
                    class="w-full rounded-xl border border-[#E5E5E5] bg-white px-4 py-3 text-xs text-[#111111] placeholder-[#A1A1A1] outline-none transition focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white font-sans"
                >{{ old('cover_letter') }}</textarea>
            </div>

            {{-- 4. Actions --}}
            <div class="flex items-center justify-end gap-3 border-t border-[#E5E5E5] pt-6 dark:border-[#262626]">
                <a
                    href="{{ route('jobs.show', $job) }}"
                    class="rounded-xl border border-[#E5E5E5] bg-white px-5 py-2.5 text-xs font-bold text-[#111111] transition hover:bg-[#F7F7F7] dark:border-[#262626] dark:bg-[#1A1A1A] dark:text-white"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-brand-500 px-6 py-2.5 text-xs font-bold text-white shadow-xs transition hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50"
                >
                    Submit Application →
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
