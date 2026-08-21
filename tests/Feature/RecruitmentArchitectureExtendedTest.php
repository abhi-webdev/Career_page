<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Employee;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Offer;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RecruitmentArchitectureExtendedTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $hr;
    protected User $tr;
    protected User $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@recruitment.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->hr = User::create([
            'name' => 'HR Specialist',
            'email' => 'hr@recruitment.test',
            'password' => Hash::make('password'),
            'role' => 'hr',
        ]);

        $this->tr = User::create([
            'name' => 'Technical Recruiter',
            'email' => 'tr@recruitment.test',
            'password' => Hash::make('password'),
            'role' => 'tr',
        ]);

        $this->candidate = User::create([
            'name' => 'John Candidate',
            'email' => 'candidate@recruitment.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }

    protected function createJob(array $attributes = []): Job
    {
        return Job::create(array_merge([
            'title' => 'Senior Backend Engineer',
            'company' => 'TechCorp Global',
            'description' => 'Build high-scale distributed backend systems.',
            'skills' => ['PHP', 'Laravel', 'PostgreSQL'],
            'location' => 'Remote',
            'job_type' => 'Full Time',
            'experience' => '3-5 years',
            'technical_interview_required' => true,
        ], $attributes));
    }

    protected function createResume(User $user): Resume
    {
        return Resume::create([
            'user_id' => $user->id,
            'file_name' => 'john_candidate_cv.pdf',
            'file_path' => 'resumes/john_candidate_cv.pdf',
        ]);
    }

    /**
     * Test 1: Admin creates a job with technical_interview_required = true or false.
     */
    public function test_admin_can_create_jobs_with_technical_interview_required_setting(): void
    {
        $this->actingAs($this->admin)->post(route('admin.jobs.store'), [
            'title' => 'Senior Backend Engineer',
            'company' => 'TechCorp Global',
            'description' => 'Build high-scale distributed backend systems.',
            'skills' => 'PHP, Laravel, PostgreSQL',
            'location' => 'Remote',
            'job_type' => 'Full Time',
            'experience' => '3-5 years',
            'technical_interview_required' => 1,
        ])->assertRedirect(route('admin.jobs.index'));

        $this->assertDatabaseHas('jobs', [
            'title' => 'Senior Backend Engineer',
            'technical_interview_required' => true,
        ]);

        $this->actingAs($this->admin)->post(route('admin.jobs.store'), [
            'title' => 'Community Manager',
            'company' => 'TechCorp Global',
            'description' => 'Manage user communities.',
            'skills' => 'Communication, Social Media',
            'location' => 'Bangalore',
            'job_type' => 'Full Time',
            'experience' => '1-2 years',
            'technical_interview_required' => 0,
        ])->assertRedirect(route('admin.jobs.index'));

        $this->assertDatabaseHas('jobs', [
            'title' => 'Community Manager',
            'technical_interview_required' => false,
        ]);
    }

    /**
     * Test 2: Pipeline Branch A - Mandatory HR Interview fails -> Application is Rejected.
     */
    public function test_mandatory_hr_interview_failure_rejects_application(): void
    {
        $job = $this->createJob([
            'title' => 'DevOps Specialist',
            'technical_interview_required' => true,
        ]);

        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        // HR Schedules HR Interview
        $this->actingAs($this->hr)->post(route('hr.applications.interview.store', $application), [
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/hr-screening-test',
            'notes' => 'Screening culture and basic fit',
        ])->assertRedirect(route('hr.applications.show', $application));

        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'type' => 'hr',
            'status' => 'scheduled',
        ]);

        // HR Completes with FAILED outcome
        $this->actingAs($this->hr)->patch(route('hr.applications.interview.complete', $application), [
            'result' => 'failed',
            'admin_feedback' => 'Candidate does not match cultural values.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'type' => 'hr',
            'status' => 'completed',
            'result' => 'failed',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test 3: Pipeline Branch B1 - Job requires Tech Interview -> HR Passes -> TR Fails -> Rejected.
     */
    public function test_technical_interview_failure_rejects_application(): void
    {
        $job = $this->createJob([
            'title' => 'Fullstack Architect',
            'technical_interview_required' => true,
        ]);

        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        // HR Schedules & Passes HR Interview
        Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '10:45',
            'meeting_link' => 'https://meet.google.com/hr-test',
            'status' => 'scheduled',
            'interviewer_id' => $this->hr->id,
        ]);

        $this->actingAs($this->hr)->patch(route('hr.applications.interview.complete', $application), [
            'result' => 'passed',
            'admin_feedback' => 'Great culture fit, strong communication.',
        ]);

        $application->refresh();
        $this->assertEquals('technical_interview', $application->status);

        // TR schedules Technical Round
        $this->actingAs($this->tr)->post(route('tr.applications.interview.store', $application), [
            'interview_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'meeting_link' => 'https://meet.google.com/tech-eval-test',
            'notes' => 'Live coding and system design',
        ])->assertRedirect(route('tr.applications.show', $application));

        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'type' => 'technical',
            'status' => 'scheduled',
        ]);

        // TR Fails Technical Round
        $this->actingAs($this->tr)->patch(route('tr.applications.interview.complete', $application), [
            'result' => 'failed',
            'admin_feedback' => 'Failed algorithm and data structures assessment.',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'type' => 'technical',
            'status' => 'completed',
            'result' => 'failed',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'rejected',
        ]);
    }

    /**
     * Test 4: Pipeline Branch B2 - Technical Job -> HR Passes -> TR Passes -> Admin Final Review -> Selected -> Offer -> Hired.
     */
    public function test_complete_technical_recruitment_pipeline_to_hire(): void
    {
        $job = $this->createJob([
            'title' => 'Principal Engineer',
            'technical_interview_required' => true,
        ]);

        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        // 1. HR Round Passes
        Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '10:45',
            'meeting_link' => 'https://meet.google.com/hr-test',
            'status' => 'scheduled',
        ]);

        $this->actingAs($this->hr)->patch(route('hr.applications.interview.complete', $application), [
            'result' => 'passed',
            'admin_feedback' => 'Excellent behavioral interview.',
        ]);

        $application->refresh();
        $this->assertEquals('technical_interview', $application->status);

        // 2. Technical Round Passes
        Interview::create([
            'application_id' => $application->id,
            'type' => 'technical',
            'interview_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '15:00',
            'end_time' => '16:00',
            'meeting_link' => 'https://meet.google.com/tech-eval',
            'status' => 'scheduled',
        ]);

        $this->actingAs($this->tr)->patch(route('tr.applications.interview.complete', $application), [
            'result' => 'passed',
            'admin_feedback' => 'Exceptional system architecture and coding skills.',
        ]);

        $application->refresh();
        $this->assertEquals('admin_review', $application->status);

        // 3. Admin Final Review -> Selects Candidate
        $this->actingAs($this->admin)->patch(route('admin.applications.status', $application), [
            'status' => 'selected',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'selected',
        ]);

        // 4. Admin Creates & Sends Offer
        $this->actingAs($this->admin)->post(route('admin.applications.offer.store', $application), [
            'salary' => 1850000.00,
            'joining_date' => now()->addDays(20)->format('Y-m-d'),
            'offer_expiry_date' => now()->addDays(7)->format('Y-m-d'),
            'terms' => 'Standard non-disclosure and full-time employment terms.',
        ])->assertRedirect(route('admin.applications.show', $application));

        $offer = Offer::where('application_id', $application->id)->first();
        $this->assertNotNull($offer);
        $this->assertEquals('draft', $offer->status);

        // Generate Offer Letter
        $this->actingAs($this->admin)->post(route('admin.applications.offer.generate-letter', $application));

        // Admin sends offer
        $this->actingAs($this->admin)->post(route('admin.applications.offer.send', $application));

        $offer->refresh();
        $this->assertEquals('sent', $offer->status);

        // 5. Candidate Uploads Signed Copy & Accepts Offer
        $file = UploadedFile::fake()->create('signed_offer.pdf', 200, 'application/pdf');

        $this->actingAs($this->candidate)->post(route('applications.offer.upload-signed', $application), [
            'signed_offer' => $file,
        ])->assertRedirect();

        $this->actingAs($this->candidate)->post(route('applications.offer.accept', $application))
            ->assertRedirect();

        $offer->refresh();
        $this->assertEquals('accepted', $offer->status);
        $this->assertNotNull($offer->signed_offer_letter_path);

        // Employee created automatically
        $employee = Employee::where('application_id', $application->id)->first();
        $this->assertNotNull($employee);
        $this->assertEquals($this->candidate->id, $employee->user_id);
    }

    /**
     * Test 5: Pipeline Branch C - Non-Technical Job -> HR Passes -> Bypasses TR directly to Admin Review.
     */
    public function test_non_technical_job_bypasses_technical_interview(): void
    {
        $job = $this->createJob([
            'title' => 'Talent Acquisition Coordinator',
            'technical_interview_required' => false,
        ]);

        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        // HR Schedules & Passes HR Interview
        Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '11:00',
            'end_time' => '11:30',
            'meeting_link' => 'https://meet.google.com/hr-test',
            'status' => 'scheduled',
        ]);

        $this->actingAs($this->hr)->patch(route('hr.applications.interview.complete', $application), [
            'result' => 'passed',
            'admin_feedback' => 'Candidate passed HR round with distinction.',
        ]);

        $application->refresh();
        // Since technical_interview_required is false, directly goes to admin_review
        $this->assertEquals('admin_review', $application->status);

        // Admin Final Review -> Selects candidate
        $this->actingAs($this->admin)->patch(route('admin.applications.status', $application), [
            'status' => 'selected',
        ]);

        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'status' => 'selected',
        ]);
    }
}
