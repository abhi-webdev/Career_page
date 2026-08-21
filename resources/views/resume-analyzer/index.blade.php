@extends('layouts.app')

@section('title', 'Resume Analyzer — ADV AIT Careers')

@section('content')

<div class="max-w-3xl mx-auto space-y-8 py-8 sm:py-12 px-4 sm:px-6">

    {{-- Breadcrumb Back --}}
    <div class="flex items-center gap-2 text-xs text-[#6B6B6B] dark:text-[#A1A1A1]">
        <a href="{{ route('home') }}" class="hover:text-brand-500 transition">
            ← Back to Home
        </a>
    </div>

    {{-- Header --}}
    <div class="text-center space-y-3 max-w-xl mx-auto">
        <div class="inline-flex items-center gap-2 rounded-full border border-brand-500/20 bg-brand-500/10 px-4 py-1.5 text-xs font-bold text-brand-500">
            <span>✨ ADV AIT Skill Match Engine</span>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight text-[#111111] sm:text-4xl dark:text-white">
            Resume Analyzer
        </h1>
        <p class="text-xs sm:text-sm text-[#6B6B6B] dark:text-[#A1A1A1] leading-relaxed">
            Find the jobs that match your skills. Upload your resume and we'll analyze your skills against our current openings.
        </p>
    </div>

    {{-- Error Banner --}}
    @if(session('error'))
        <div class="rounded-2xl border border-red-500/30 bg-red-500/10 p-4 text-xs font-semibold text-red-600 dark:text-red-400 flex items-start gap-3">
            <span class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">!</span>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Upload Card --}}
    <div class="rounded-3xl border border-[#E5E5E5] bg-white p-6 sm:p-10 dark:border-[#262626] dark:bg-[#141414] shadow-sm">
        
        <form
            id="analyzer-form"
            action="{{ route('resume-analyzer.analyze') }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-6"
            onsubmit="handleAnalyzerSubmit(event)"
        >
            @csrf

            {{-- File Upload Drag & Drop Area --}}
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-[#111111] dark:text-white mb-2">
                    Upload Resume Document <span class="text-red-500">*</span>
                </label>

                <div class="relative flex flex-col items-center justify-center rounded-2xl border-2 border-dashed border-[#E5E5E5] bg-[#F7F7F7] p-8 sm:p-12 text-center transition hover:border-brand-500 hover:bg-orange-500/5 dark:border-[#262626] dark:bg-[#1A1A1A] dark:hover:border-brand-500">
                    <span class="text-4xl">📄</span>
                    <p class="mt-3 text-xs font-bold text-[#111111] dark:text-white">
                        Click or drag your resume to upload
                    </p>
                    <p class="mt-1 text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                        Supported format: PDF, DOC, DOCX, TXT (Max size: 5MB)
                    </p>
                    <p id="selected-file-display" class="mt-3 text-xs font-bold text-brand-500 hidden"></p>

                    <input
                        id="resume-file-input"
                        type="file"
                        name="resume"
                        accept=".pdf,.doc,.docx,.txt"
                        required
                        class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
                        onchange="updateSelectedFile(this)"
                    >
                </div>

                @error('resume')
                    <p class="mt-1.5 text-xs font-semibold text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Dynamic Progress State Container (Hidden by default) --}}
            <div id="analyzer-progress" class="hidden rounded-2xl border border-brand-500/30 bg-orange-500/5 p-6 dark:bg-[#1A1A1A] text-center space-y-3">
                <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-full bg-brand-500/10 text-brand-500 animate-spin">
                    ⏳
                </div>
                <h3 id="progress-status-title" class="text-xs font-bold uppercase tracking-wider text-brand-500">
                    Uploading resume...
                </h3>
                <p id="progress-status-desc" class="text-[11px] text-[#6B6B6B] dark:text-[#A1A1A1]">
                    Extracting technical keywords and matching against open Advait opportunities...
                </p>
            </div>

            {{-- Submit Button --}}
            <button
                id="submit-btn"
                type="submit"
                class="w-full rounded-2xl bg-brand-500 py-3.5 text-xs font-bold text-white shadow-md hover:bg-brand-600 focus:ring-2 focus:ring-brand-500/50 transition flex items-center justify-center gap-2"
            >
                <span>🔍 Analyze Resume</span>
                <span>→</span>
            </button>
        </form>

    </div>

</div>

<script>
    function updateSelectedFile(input) {
        const display = document.getElementById('selected-file-display');
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const sizeKb = (file.size / 1024).toFixed(1);
            display.textContent = `✓ Selected: ${file.name} (${sizeKb} KB)`;
            display.classList.remove('hidden');
        } else {
            display.textContent = '';
            display.classList.add('hidden');
        }
    }

    function handleAnalyzerSubmit(e) {
        const fileInput = document.getElementById('resume-file-input');
        if (!fileInput.files || !fileInput.files[0]) {
            return;
        }

        const submitBtn = document.getElementById('submit-btn');
        const progressBox = document.getElementById('analyzer-progress');
        const statusTitle = document.getElementById('progress-status-title');
        const statusDesc = document.getElementById('progress-status-desc');

        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        submitBtn.textContent = 'Processing Resume...';
        progressBox.classList.remove('hidden');

        // Progressive user feedback sequence
        setTimeout(() => {
            if (statusTitle) statusTitle.textContent = 'Extracting skills...';
            if (statusDesc) statusDesc.textContent = 'Finding your programming languages, frameworks, and tools...';
        }, 800);

        setTimeout(() => {
            if (statusTitle) statusTitle.textContent = 'Matching opportunities...';
            if (statusDesc) statusDesc.textContent = 'Comparing skills against current Advait job openings...';
        }, 1800);
    }
</script>

@endsection
