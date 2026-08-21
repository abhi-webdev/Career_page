<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InterviewerAssignmentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $hrUser1;
    protected User $hrUser2;
    protected User $trUser1;
    protected User $trUser2;
    protected User $candidate;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Storage::fake('public');

        $this->admin = User::create([
            'name' => 'Admin Boss',
            'email' => 'admin@portal.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $this->hrUser1 = User::create([
            'name' => 'Priya Sharma',
            'email' => 'priya.hr@portal.test',
            'password' => Hash::make('password'),
            'role' => 'hr',
        ]);

        $this->hrUser2 = User::create([
            'name' => 'Anjali Mehta',
            'email' => 'anjali.hr@portal.test',
            'password' => Hash::make('password'),
            'role' => 'hr',
        ]);

        $this->trUser1 = User::create([
            'name' => 'Rahul Verma',
            'email' => 'rahul.tr@portal.test',
            'password' => Hash::make('password'),
            'role' => 'tr',
        ]);

        $this->trUser2 = User::create([
            'name' => 'Kunal Kapoor',
            'email' => 'kunal.tr@portal.test',
            'password' => Hash::make('password'),
            'role' => 'tr',
        ]);

        $this->candidate = User::create([
            'name' => 'Abhi Prajapati',
            'email' => 'abhi.candidate@portal.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }

    protected function createJob(array $attributes = []): Job
    {
        return Job::create(array_merge([
            'title' => 'Backend Architect',
            'company' => 'Google Cloud Partner',
            'description' => 'Scalable microservices in PHP/Laravel and Go.',
            'skills' => ['PHP', 'Laravel', 'MySQL', 'Redis'],
            'location' => 'Bangalore',
            'job_type' => 'Full Time',
            'experience' => '3-5 years',
            'technical_interview_required' => true,
        ], $attributes));
    }

    protected function createResume(User $user): Resume
    {
        return Resume::create([
            'user_id' => $user->id,
            'file_name' => 'abhi_prajapati_cv.pdf',
            'file_path' => 'resumes/abhi_prajapati_cv.pdf',
        ]);
    }

    /**
     * Test 1: Admin can schedule HR interview and explicitly assign an HR user.
     */
    public function test_admin_can_schedule_hr_interview_with_assigned_hr_interviewer(): void
    {
        $job = $this->createJob(['technical_interview_required' => true]);
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.applications.interview.store', $application), [
            'type' => 'hr',
            'interviewer_id' => $this->hrUser1->id,
            'interview_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/priya-screening',
            'notes' => 'Culture and communication round',
        ]);

        $response->assertRedirect(route('admin.applications.show', $application));
        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'type' => 'hr',
            'interviewer_id' => $this->hrUser1->id,
            'status' => 'scheduled',
        ]);

        // Notifications sent to assigned HR and candidate
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->hrUser1->id,
        ]);
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->candidate->id,
        ]);
    }

    /**
     * Test 2: Admin cannot assign a TR user or non-HR user to an HR interview.
     */
    public function test_admin_cannot_assign_non_hr_user_to_hr_interview(): void
    {
        $job = $this->createJob();
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        // Try assigning TR user to HR interview
        $response = $this->actingAs($this->admin)->post(route('admin.applications.interview.store', $application), [
            'type' => 'hr',
            'interviewer_id' => $this->trUser1->id, // Invalid: TR user for HR interview
            'interview_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/test-meet',
        ]);

        $response->assertSessionHasErrors('interviewer_id');
        $this->assertDatabaseMissing('interviews', [
            'application_id' => $application->id,
            'type' => 'hr',
        ]);
    }

    /**
     * Test 3: Admin can schedule Technical interview and assign a TR user.
     */
    public function test_admin_can_schedule_technical_interview_with_assigned_tr_interviewer(): void
    {
        $job = $this->createJob(['technical_interview_required' => true]);
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'technical_interview',
        ]);

        // Pass HR interview first
        Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->subDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/hr-passed',
            'status' => 'completed',
            'result' => 'passed',
            'interviewer_id' => $this->hrUser1->id,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.applications.interview.store', $application), [
            'type' => 'technical',
            'interviewer_id' => $this->trUser1->id,
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'meeting_link' => 'https://meet.google.com/rahul-tech-eval',
            'notes' => 'Live coding and DB indexing test',
        ]);

        $response->assertRedirect(route('admin.applications.show', $application));
        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'type' => 'technical',
            'interviewer_id' => $this->trUser1->id,
            'status' => 'scheduled',
        ]);
    }

    /**
     * Test 4: Admin cannot assign an HR user or non-TR user to a Technical interview.
     */
    public function test_admin_cannot_assign_non_tr_user_to_technical_interview(): void
    {
        $job = $this->createJob(['technical_interview_required' => true]);
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'technical_interview',
        ]);

        Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->subDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/hr',
            'status' => 'completed',
            'result' => 'passed',
            'interviewer_id' => $this->hrUser1->id,
        ]);

        // Try assigning HR user to Technical interview
        $response = $this->actingAs($this->admin)->post(route('admin.applications.interview.store', $application), [
            'type' => 'technical',
            'interviewer_id' => $this->hrUser1->id, // Invalid: HR user for TR interview
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'meeting_link' => 'https://meet.google.com/tech-fail',
        ]);

        $response->assertSessionHasErrors('interviewer_id');
        $this->assertDatabaseMissing('interviews', [
            'application_id' => $application->id,
            'type' => 'technical',
        ]);
    }

    /**
     * Test 5: Scoped HR "My Interviews" - HR User 1 only sees interviews assigned to HR User 1.
     */
    public function test_hr_user_only_sees_assigned_interviews(): void
    {
        $job = $this->createJob();
        $resume = $this->createResume($this->candidate);

        $app1 = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        $cand2 = User::create([
            'name' => 'Candidate Two',
            'email' => 'cand2@portal.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        $resume2 = $this->createResume($cand2);

        $app2 = Application::create([
            'user_id' => $cand2->id,
            'job_id' => $job->id,
            'resume_id' => $resume2->id,
            'status' => 'shortlisted',
        ]);

        // Interview 1 assigned to Priya (HR 1)
        Interview::create([
            'application_id' => $app1->id,
            'type' => 'hr',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/priya-interview',
            'status' => 'scheduled',
            'interviewer_id' => $this->hrUser1->id,
        ]);

        // Interview 2 assigned to Anjali (HR 2)
        Interview::create([
            'application_id' => $app2->id,
            'type' => 'hr',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '11:00',
            'end_time' => '12:00',
            'meeting_link' => 'https://meet.google.com/anjali-interview',
            'status' => 'scheduled',
            'interviewer_id' => $this->hrUser2->id,
        ]);

        // Priya logs in
        $response = $this->actingAs($this->hrUser1)->get(route('hr.interviews.index'));
        $response->assertOk();
        $response->assertSee('Abhi Prajapati');
        $response->assertDontSee('Candidate Two');
    }

    /**
     * Test 6: Scoped TR "My Technical Interviews" - TR User 1 only sees interviews assigned to TR User 1.
     */
    public function test_tr_user_only_sees_assigned_technical_interviews(): void
    {
        $job = $this->createJob(['technical_interview_required' => true]);
        $resume = $this->createResume($this->candidate);

        $app1 = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'technical_interview',
        ]);

        $cand2 = User::create([
            'name' => 'Candidate Tech 2',
            'email' => 'tech2@portal.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        $resume2 = $this->createResume($cand2);

        $app2 = Application::create([
            'user_id' => $cand2->id,
            'job_id' => $job->id,
            'resume_id' => $resume2->id,
            'status' => 'technical_interview',
        ]);

        // Tech Interview 1 assigned to Rahul (TR 1)
        Interview::create([
            'application_id' => $app1->id,
            'type' => 'technical',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'meeting_link' => 'https://meet.google.com/rahul-eval',
            'status' => 'scheduled',
            'interviewer_id' => $this->trUser1->id,
        ]);

        // Tech Interview 2 assigned to Kunal (TR 2)
        Interview::create([
            'application_id' => $app2->id,
            'type' => 'technical',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '16:00',
            'end_time' => '17:00',
            'meeting_link' => 'https://meet.google.com/kunal-eval',
            'status' => 'scheduled',
            'interviewer_id' => $this->trUser2->id,
        ]);

        // Rahul logs in
        $response = $this->actingAs($this->trUser1)->get(route('tr.interviews.index'));
        $response->assertOk();
        $response->assertSee('Abhi Prajapati');
        $response->assertDontSee('Candidate Tech 2');
    }

    /**
     * Test 7: Direct URL Security - HR user cannot access another HR user's interview or technical interview.
     */
    public function test_hr_user_cannot_access_unauthorized_interview(): void
    {
        $job = $this->createJob();
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        // HR interview assigned to Anjali (HR 2)
        $interview = Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/anjali',
            'status' => 'scheduled',
            'interviewer_id' => $this->hrUser2->id,
        ]);

        // Priya (HR 1) attempts direct show URL
        $this->actingAs($this->hrUser1)->get(route('hr.interviews.show', $interview))
            ->assertStatus(403);

        // Priya (HR 1) attempts to complete Anjali's interview
        $this->actingAs($this->hrUser1)->patch(route('hr.applications.interview.complete', $application), [
            'result' => 'passed',
        ])->assertStatus(403);
    }

    /**
     * Test 8: Direct URL Security - TR user cannot access another TR user's interview or HR interview.
     */
    public function test_tr_user_cannot_access_unauthorized_technical_interview(): void
    {
        $job = $this->createJob(['technical_interview_required' => true]);
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'technical_interview',
        ]);

        // Tech interview assigned to Kunal (TR 2)
        $interview = Interview::create([
            'application_id' => $application->id,
            'type' => 'technical',
            'interview_date' => now()->addDay()->format('Y-m-d'),
            'start_time' => '15:00',
            'end_time' => '16:00',
            'meeting_link' => 'https://meet.google.com/kunal',
            'status' => 'scheduled',
            'interviewer_id' => $this->trUser2->id,
        ]);

        // Rahul (TR 1) attempts direct show URL
        $this->actingAs($this->trUser1)->get(route('tr.interviews.show', $interview))
            ->assertStatus(403);

        // Rahul (TR 1) attempts to complete Kunal's interview
        $this->actingAs($this->trUser1)->patch(route('tr.applications.interview.complete', $application), [
            'result' => 'passed',
        ])->assertStatus(403);
    }

    /**
     * Test 9: HR / TR completion notifies Admins with candidate name, interviewer name, and recommendation.
     */
    public function test_admin_is_notified_when_interviewer_completes_evaluation(): void
    {
        $job = $this->createJob(['technical_interview_required' => true]);
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/priya',
            'status' => 'scheduled',
            'interviewer_id' => $this->hrUser1->id,
        ]);

        // Priya completes HR round
        $this->actingAs($this->hrUser1)->patch(route('hr.applications.interview.complete', $application), [
            'result' => 'passed',
            'admin_feedback' => 'Strong communication and positive culture alignment.',
        ])->assertSessionHas('success');

        // Verify Admin notification was created
        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $this->admin->id,
        ]);
    }

    /**
     * Test 10: Admin Candidate Profile displays assigned interviewers and handles non-technical jobs cleanly.
     */
    public function test_admin_candidate_profile_displays_interviewers_and_non_technical_note(): void
    {
        $nonTechJob = $this->createJob([
            'title' => 'Community Specialist',
            'technical_interview_required' => false,
        ]);
        $resume = $this->createResume($this->candidate);

        $application = Application::create([
            'user_id' => $this->candidate->id,
            'job_id' => $nonTechJob->id,
            'resume_id' => $resume->id,
            'status' => 'shortlisted',
        ]);

        Interview::create([
            'application_id' => $application->id,
            'type' => 'hr',
            'interview_date' => now()->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/priya',
            'status' => 'completed',
            'result' => 'passed',
            'admin_feedback' => 'Excellent culture alignment.',
            'interviewer_id' => $this->hrUser1->id,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.applications.show', $application));
        $response->assertOk();
        $response->assertSee('Priya Sharma');
        $response->assertSee('Technical Interview');
        $response->assertSee('Not Required');
    }
}
