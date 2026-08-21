<?php

namespace App\Http\Controllers;

use App\Services\ResumeAnalyzerService;
use Illuminate\Http\Request;

class ResumeAnalyzerController extends Controller
{
    protected ResumeAnalyzerService $analyzer;

    public function __construct(ResumeAnalyzerService $analyzer)
    {
        $this->analyzer = $analyzer;
    }

    /**
     * Show Resume Analyzer upload page.
     */
    public function index()
    {
        return view('resume-analyzer.index');
    }

    /**
     * Analyze uploaded resume against all currently available jobs.
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx,txt|max:5120',
        ], [
            'resume.required' => 'Please select a resume file to upload.',
            'resume.mimes' => 'The resume must be a file of type: PDF, DOC, DOCX, or TXT.',
            'resume.max' => 'The resume file size must not exceed 5MB.',
        ]);

        try {
            $file = $request->file('resume');

            // Store uploaded resume temporarily or in public storage
            if (auth()->check()) {
                $extension = $file->getClientOriginalExtension();
                $safeBase = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $fileName = time() . '_' . $safeBase . '.' . $extension;
                $filePath = $file->storeAs('resumes', $fileName, 'public');

                auth()->user()->resumes()->create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $filePath,
                ]);
            }

            $analysis = $this->analyzer->analyze($file);

            if (isset($analysis['status']) && $analysis['status'] === 'no_text') {
                return back()->with('error', $analysis['message'])->withInput();
            }

            return view('resume-analyzer.result', compact('analysis'));
        } catch (\Exception $e) {
            logger()->error('Resume Analyzer Error: ' . $e->getMessage());
            return back()->with('error', 'Unable to upload and analyze your resume. Please check the file format and try again.')->withInput();
        }
    }
}
