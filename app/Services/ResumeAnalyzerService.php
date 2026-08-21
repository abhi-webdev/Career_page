<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Http\UploadedFile;
use ZipArchive;

class ResumeAnalyzerService
{
    /**
     * Common skill keywords dictionary.
     */
    protected array $skillDictionary = [
        'php', 'laravel', 'javascript', 'typescript', 'react', 'react.js', 'next.js', 'vue', 'vue.js',
        'angular', 'node.js', 'nodejs', 'express', 'express.js', 'nest.js', 'python', 'django', 'fastapi',
        'flask', 'java', 'spring', 'spring boot', 'c++', 'c#', '.net', 'golang', 'go', 'rust',
        'mysql', 'postgresql', 'postgres', 'mongodb', 'redis', 'elasticsearch', 'sqlite', 'sql',
        'docker', 'kubernetes', 'aws', 'amazon web services', 'azure', 'gcp', 'google cloud',
        'git', 'github', 'gitlab', 'ci/cd', 'jenkins', 'linux', 'rest api', 'graphql',
        'html', 'css', 'tailwind', 'tailwindcss', 'bootstrap', 'system design', 'microservices',
        'oop', 'rest', 'api', 'devops', 'testing', 'phpunit', 'jest', 'kafka', 'rabbitmq'
    ];

    /**
     * Extract text and analyze resume automatically against all active database jobs.
     */
    public function analyze(UploadedFile $file): array
    {
        $rawText = $this->extractText($file);
        $cleanText = trim(preg_replace('/\s+/', ' ', $rawText));

        // Check if readable text was extracted (at least 15 alphanumeric characters)
        $alphanumericCount = preg_match_all('/[a-zA-Z0-9]/', $cleanText);
        if ($alphanumericCount < 15) {
            return [
                'status' => 'no_text',
                'fileName' => $file->getClientOriginalName(),
                'message' => "We couldn't extract readable text from this resume. Please upload a text-based PDF or document.",
                'detectedSkills' => [],
                'recommendations' => [],
            ];
        }

        $normalizedText = ' ' . strtolower($cleanText) . ' ';

        // Gather all unique skills from active database jobs
        $activeJobs = Job::query()
            ->where(function ($q) {
                $q->whereNull('application_deadline')
                  ->orWhere('application_deadline', '>=', now());
            })
            ->latest()
            ->get();

        $dbSkills = $activeJobs->pluck('skills')
            ->flatten()
            ->filter()
            ->unique()
            ->map(fn($s) => strtolower(trim($s)))
            ->toArray();

        $allKeywords = array_unique(array_merge($this->skillDictionary, $dbSkills));

        // Find detected skills in resume
        $detectedSkills = [];
        foreach ($allKeywords as $keyword) {
            $keywordTrimmed = trim($keyword);
            if ($keywordTrimmed === '' || strlen($keywordTrimmed) < 2) continue;

            $escaped = preg_quote($keywordTrimmed, '/');
            // Support keywords with dots/symbols like .net, node.js, c++
            if (preg_match('/[+#.]/', $keywordTrimmed)) {
                $pattern = '/(?<=[\s,;.()]|^)' . $escaped . '(?=[\s,;.()]|$)/i';
            } else {
                $pattern = '/\b' . $escaped . '\b/i';
            }

            if (preg_match($pattern, $normalizedText)) {
                $detectedSkills[] = ucwords($keywordTrimmed);
            }
        }

        $detectedSkills = array_values(array_unique($detectedSkills));

        // Compare detected skills against each active job opening
        $recommendations = [];

        foreach ($activeJobs as $job) {
            $requiredSkills = is_array($job->skills) ? $job->skills : [];
            if (empty($requiredSkills)) {
                // If job has no explicit skills array, extract keywords from requirements/description
                $jobSkillsString = strtolower($job->title . ' ' . $job->requirements . ' ' . $job->description);
                $fallbackSkills = [];
                foreach ($allKeywords as $kw) {
                    if (str_contains($jobSkillsString, strtolower($kw))) {
                        $fallbackSkills[] = ucwords($kw);
                    }
                }
                $requiredSkills = !empty($fallbackSkills) ? array_slice($fallbackSkills, 0, 5) : ['PHP', 'Laravel', 'SQL'];
            }

            $matchedForJob = [];
            $missingForJob = [];

            foreach ($requiredSkills as $req) {
                $reqNormalized = strtolower(trim($req));
                $found = false;
                foreach ($detectedSkills as $det) {
                    $detNormalized = strtolower(trim($det));
                    if ($detNormalized === $reqNormalized || str_contains($detNormalized, $reqNormalized) || str_contains($reqNormalized, $detNormalized)) {
                        $matchedForJob[] = $req;
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $missingForJob[] = $req;
                }
            }

            $totalRequired = count($requiredSkills);
            $totalMatched = count($matchedForJob);
            $score = $totalRequired > 0 ? (int) round(($totalMatched / $totalRequired) * 100) : 0;

            $recommendations[] = [
                'job' => $job,
                'score' => $score,
                'matched_skills' => $matchedForJob,
                'missing_skills' => $missingForJob,
            ];
        }

        // Sort recommendations by match score descending
        usort($recommendations, fn($a, $b) => $b['score'] <=> $a['score']);

        return [
            'status' => 'success',
            'fileName' => $file->getClientOriginalName(),
            'detectedSkills' => $detectedSkills,
            'recommendations' => $recommendations,
            'totalActiveJobs' => $activeJobs->count(),
        ];
    }

    /**
     * Extract plain text from PDF, DOCX, or TXT file.
     */
    public function extractText(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $path = $file->getPathname();

        if ($extension === 'txt') {
            return (string) @file_get_contents($path);
        }

        if ($extension === 'docx') {
            return $this->extractFromDocx($path);
        }

        if ($extension === 'pdf') {
            return $this->extractFromPdf($path);
        }

        return (string) @file_get_contents($path);
    }

    /**
     * Extract text from DOCX archive.
     */
    protected function extractFromDocx(string $filePath): string
    {
        $zip = new ZipArchive();
        if ($zip->open($filePath) === true) {
            $xmlIndex = $zip->locateName('word/document.xml');
            if ($xmlIndex !== false) {
                $xmlData = $zip->getFromIndex($xmlIndex);
                $zip->close();
                return strip_tags(str_replace(['</w:p>', '</w:tr>'], " \n", $xmlData));
            }
            $zip->close();
        }
        return '';
    }

    /**
     * Multi-strategy PDF text extractor in pure PHP.
     */
    protected function extractFromPdf(string $filePath): string
    {
        $content = @file_get_contents($filePath);
        if (!$content) {
            return '';
        }

        $text = '';

        // Strategy 1: Find all streams (FlateDecode compressed or plain)
        if (preg_match_all('/stream\r?\n(.*?)endstream/s', $content, $matches)) {
            foreach ($matches[1] as $stream) {
                // Try decompressing flate stream
                $decompressed = @gzuncompress($stream);
                if ($decompressed === false) {
                    $decompressed = @gzinflate($stream);
                }
                $streamData = $decompressed !== false ? $decompressed : $stream;

                // Extract standard Tj / ' / " literal strings
                if (preg_match_all('/\((.*?)\)\s*(?:Tj|\'|\")/s', $streamData, $tjMatches)) {
                    foreach ($tjMatches[1] as $match) {
                        $text .= ' ' . $this->decodePdfString($match);
                    }
                }

                // Extract TJ array chunks: [ (chunk) 120 (chunk2) ] TJ
                if (preg_match_all('/\[(.*?)\]\s*TJ/s', $streamData, $tjArrayMatches)) {
                    foreach ($tjArrayMatches[1] as $arrayContent) {
                        if (preg_match_all('/\((.*?)\)/s', $arrayContent, $chunkMatches)) {
                            foreach ($chunkMatches[1] as $chunk) {
                                $text .= ' ' . $this->decodePdfString($chunk);
                            }
                        }
                    }
                }

                // Extract Hex encoded strings: <48656c6c6f> Tj or in TJ array
                if (preg_match_all('/<([0-9a-fA-F\s]+)>\s*Tj/s', $streamData, $hexMatches)) {
                    foreach ($hexMatches[1] as $hex) {
                        $cleanedHex = preg_replace('/\s+/', '', $hex);
                        if (strlen($cleanedHex) % 2 === 0) {
                            $decoded = @hex2bin($cleanedHex);
                            if ($decoded !== false) {
                                $text .= ' ' . $decoded;
                            }
                        }
                    }
                }
            }
        }

        // Strategy 2: If stream parsing extracted very little, fallback to search across whole PDF object tree
        if (strlen(trim($text)) < 20) {
            if (preg_match_all('/\(([a-zA-Z0-9\s.,\-_#+@\/:]{3,})\)/', $content, $plainMatches)) {
                $text .= ' ' . implode(' ', $plainMatches[1]);
            }
        }

        // Strategy 3: Clean up and unescape
        $cleaned = preg_replace('/[^\x20-\x7E\n\r\t]/', ' ', $text);
        return preg_replace('/\s+/', ' ', $cleaned);
    }

    /**
     * Decode PDF string escape sequences.
     */
    protected function decodePdfString(string $str): string
    {
        $str = str_replace(
            ['\\n', '\\r', '\\t', '\\(', '\\)', '\\\\'],
            ["\n", "\r", "\t", '(', ')', '\\'],
            $str
        );
        return $str;
    }
}
