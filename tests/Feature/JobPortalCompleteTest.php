<?php

namespace Tests\Feature;

use App\Mail\InterviewCancelled;
use App\Mail\InterviewRescheduled;
use App\Mail\InterviewScheduled;
use App\Mail\JobApplied;
use App\Mail\OfferSent;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Offer;
use App\Models\Resume;
use App\Models\User;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class JobPortalCompleteTest extends TestCase
{
    use RefreshDatabase;

    protected function createCandidate(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Jane Candidate',
            'email' => 'candidate@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ], $attributes));
    }

    protected function createAdmin(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ], $attributes));
    }

    protected function createJob(array $attributes = []): Job
    {
        return Job::create(array_merge([
            'title' => 'Senior Laravel Developer',
            'company' => 'TechCorp Global',
            'description' => 'We are seeking an experienced Laravel engineer.',
            'skills' => ['PHP', 'Laravel', 'MySQL', 'Tailwind'],
            'location' => 'Remote',
            'job_type' => 'Full Time',
            'experience' => '3-5 years',
            'application_deadline' => now()->addDays(30),
        ], $attributes));
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Authentication & Role Separation
    |--------------------------------------------------------------------------
    */

    public function test_candidate_can_register_and_is_redirected_to_dashboard(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Alex Developer',
            'email' => 'alex@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'alex@example.com',
            'role' => 'user',
        ]);
    }

    public function test_candidate_login_redirects_to_candidate_dashboard(): void
    {
        $candidate = $this->createCandidate();

        $response = $this->post(route('login.store'), [
            'email' => $candidate->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($candidate);
    }

    public function test_admin_login_redirects_to_admin_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->post(route('login.store'), [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_candidate_is_forbidden_from_admin_routes(): void
    {
        $candidate = $this->createCandidate();

        $response = $this->actingAs($candidate)->get(route('admin.dashboard'));
        $response->assertStatus(403);

        $response = $this->actingAs($candidate)->get(route('admin.jobs.index'));
        $response->assertStatus(403);
    }

    public function test_admin_accessing_candidate_dashboard_is_redirected_to_admin_dashboard(): void
    {
        $admin = $this->createAdmin();

        $response = $this->actingAs($admin)->get(route('dashboard'));
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_unauthenticated_user_cannot_access_protected_routes(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('applications.index'))->assertRedirect(route('login'));
        $this->get(route('profile'))->assertRedirect(route('login'));
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Job Management (CRUD)
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_create_update_and_delete_jobs(): void
    {
        $admin = $this->createAdmin();

        // Create
        $response = $this->actingAs($admin)->post(route('admin.jobs.store'), [
            'title' => 'DevOps Engineer',
            'company' => 'CloudTech Inc',
            'description' => 'Manage AWS infrastructure and CI/CD pipelines.',
            'skills' => 'AWS, Docker, Kubernetes, Terraform',
            'location' => 'Bangalore',
            'job_type' => 'Full Time',
            'experience' => '2-4 years',
            'application_deadline' => now()->addDays(14)->format('Y-m-d H:i:s'),
        ]);

        $response->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseHas('jobs', [
            'title' => 'DevOps Engineer',
            'company' => 'CloudTech Inc',
        ]);

        $job = Job::where('title', 'DevOps Engineer')->first();
        $this->assertIsArray($job->skills);
        $this->assertContains('AWS', $job->skills);

        // Update
        $response = $this->actingAs($admin)->put(route('admin.jobs.update', $job), [
            'title' => 'Senior DevOps Engineer',
            'company' => 'CloudTech Inc',
            'description' => 'Updated job description.',
            'skills' => 'AWS, Docker, Kubernetes, Terraform, Python',
            'location' => 'Remote',
            'job_type' => 'Full Time',
            'experience' => '4-6 years',
        ]);

        $response->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseHas('jobs', [
            'id' => $job->id,
            'title' => 'Senior DevOps Engineer',
            'location' => 'Remote',
        ]);

        // Delete
        $response = $this->actingAs($admin)->delete(route('admin.jobs.destroy', $job));
        $response->assertRedirect(route('admin.jobs.index'));
        $this->assertDatabaseMissing('jobs', ['id' => $job->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Candidate Application Submission & Deadlines
    |--------------------------------------------------------------------------
    */

    public function test_candidate_can_apply_for_a_job_with_new_resume(): void
    {
        Mail::fake();
        Notification::fake();
        Storage::fake('public');
        $candidate = $this->createCandidate();
        $job = $this->createJob();

        $file = UploadedFile::fake()->create('alex_cv.pdf', 150, 'application/pdf');

        $response = $this->actingAs($candidate)->post(route('applications.store', $job), [
            'resume' => $file,
            'cover_letter' => 'I am enthusiastic about applying for this position.',
        ]);

        $response->assertRedirect(route('applications.index'));
        $this->assertDatabaseHas('applications', [
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('resumes', [
            'user_id' => $candidate->id,
            'file_name' => 'alex_cv.pdf',
        ]);

        Mail::assertSent(JobApplied::class, function ($mail) use ($candidate, $job) {
            return $mail->hasTo($candidate->email) &&
                   $mail->application->job_id === $job->id;
        });

        Notification::assertSentTo($candidate, ApplicationStatusNotification::class);
    }

    public function test_candidate_cannot_apply_twice_for_the_same_job(): void
    {
        Storage::fake('public');
        $candidate = $this->createCandidate();
        $job = $this->createJob();

        $resume = Resume::create([
            'user_id' => $candidate->id,
            'file_name' => 'resume.pdf',
            'file_path' => 'resumes/resume.pdf',
        ]);

        Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($candidate)->post(route('applications.store', $job), [
            'resume_id' => $resume->id,
        ]);

        $response->assertRedirect(route('jobs.show', $job));
        $response->assertSessionHas('error');
    }

    public function test_candidate_cannot_apply_after_deadline_has_passed(): void
    {
        $candidate = $this->createCandidate();
        $job = $this->createJob([
            'application_deadline' => now()->subDay(),
        ]);

        $resume = Resume::create([
            'user_id' => $candidate->id,
            'file_name' => 'resume.pdf',
            'file_path' => 'resumes/resume.pdf',
        ]);

        $response = $this->actingAs($candidate)->post(route('applications.store', $job), [
            'resume_id' => $resume->id,
        ]);

        $response->assertRedirect(route('jobs.show', $job));
        $response->assertSessionHas('error', 'The application deadline for this job has passed.');
        $this->assertDatabaseMissing('applications', ['job_id' => $job->id, 'user_id' => $candidate->id]);
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Candidate Isolation & Security
    |--------------------------------------------------------------------------
    */

    public function test_candidate_cannot_accept_or_decline_another_candidates_offer(): void
    {
        $candidate1 = $this->createCandidate(['email' => 'c1@example.com']);
        $candidate2 = $this->createCandidate(['email' => 'c2@example.com']);
        $job = $this->createJob();

        $application = Application::create([
            'user_id' => $candidate1->id,
            'job_id' => $job->id,
            'status' => 'selected',
        ]);

        Offer::create([
            'application_id' => $application->id,
            'salary' => 800000,
            'joining_date' => now()->addDays(20),
            'status' => 'sent',
        ]);

        // Candidate 2 tries to accept Candidate 1's offer
        $response = $this->actingAs($candidate2)->post(route('applications.offer.accept', $application));
        $response->assertStatus(403);
        $this->assertDatabaseHas('offers', ['application_id' => $application->id, 'status' => 'sent']);

        // Candidate 2 tries to decline Candidate 1's offer
        $response = $this->actingAs($candidate2)->post(route('applications.offer.decline', $application));
        $response->assertStatus(403);
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Interview Pipeline (Schedule, Reschedule, Cancel, Complete)
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_schedule_reschedule_cancel_and_complete_interview(): void
    {
        Mail::fake();
        Notification::fake();

        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'shortlisted',
        ]);

        // 1. Schedule Interview
        $response = $this->actingAs($admin)->post(route('admin.applications.interview.store', $application), [
            'interview_date' => now()->addDays(3)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'notes' => 'Technical evaluation round',
        ]);

        $response->assertRedirect(route('admin.applications.show', $application));
        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'status' => 'scheduled',
        ]);
        $this->assertEquals('interview', $application->fresh()->status);
        Mail::assertSent(InterviewScheduled::class);
        Notification::assertSentTo($candidate, ApplicationStatusNotification::class);

        // 2. Reschedule Interview
        $response = $this->actingAs($admin)->post(route('admin.applications.interview.store', $application), [
            'interview_date' => now()->addDays(5)->format('Y-m-d'),
            'start_time' => '14:00',
            'end_time' => '15:00',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'notes' => 'Rescheduled due to panel availability',
        ]);

        $response->assertRedirect(route('admin.applications.show', $application));
        Mail::assertSent(InterviewRescheduled::class);

        // 3. Cancel Interview
        $response = $this->actingAs($admin)->patch(route('admin.applications.interview.cancel', $application));
        $response->assertRedirect();
        $this->assertEquals('cancelled', $application->interview->fresh()->status);
        $this->assertEquals('shortlisted', $application->fresh()->status);
        Mail::assertSent(InterviewCancelled::class);

        // Reschedule again to test completion
        $this->actingAs($admin)->post(route('admin.applications.interview.store', $application), [
            'interview_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
        ]);

        // 4. Complete Interview
        $response = $this->actingAs($admin)->patch(route('admin.applications.interview.complete', $application));
        $response->assertRedirect();
        $this->assertEquals('completed', $application->interview->fresh()->status);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Offer Pipeline (Draft, PDF Generation, Send, Accept, Decline)
    |--------------------------------------------------------------------------
    */

    public function test_complete_offer_lifecycle_and_candidate_acceptance(): void
    {
        Mail::fake();
        Notification::fake();
        Storage::fake('public');

        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'selected',
        ]);

        Interview::create([
            'application_id' => $application->id,
            'interview_date' => now()->subDay(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/test',
            'status' => 'completed',
        ]);

        // 1. Create Offer Draft
        $response = $this->actingAs($admin)->post(route('admin.applications.offer.store', $application), [
            'salary' => 950000,
            'joining_date' => now()->addDays(30)->format('Y-m-d'),
            'offer_expiry_date' => now()->addDays(7)->format('Y-m-d'),
            'notes' => 'Stock options included.',
        ]);

        $response->assertRedirect(route('admin.applications.show', $application));
        $this->assertDatabaseHas('offers', [
            'application_id' => $application->id,
            'salary' => 950000,
            'status' => 'draft',
        ]);

        // 2. Generate PDF
        $response = $this->actingAs($admin)->post(route('admin.applications.offer.generate-letter', $application));
        $response->assertRedirect();
        $offer = $application->offer->fresh();
        $this->assertNotNull($offer->offer_letter_path);
        Storage::disk('public')->assertExists($offer->offer_letter_path);

        // 3. Send Offer
        $response = $this->actingAs($admin)->post(route('admin.applications.offer.send', $application));
        $response->assertRedirect();
        $this->assertEquals('sent', $offer->fresh()->status);
        Mail::assertSent(OfferSent::class);
        Notification::assertSentTo($candidate, ApplicationStatusNotification::class);

        // 4. Candidate uploads signed offer
        $file = UploadedFile::fake()->create('signed_offer.pdf', 200, 'application/pdf');
        $this->actingAs($candidate)->post(route('applications.offer.upload-signed', $application), [
            'signed_offer' => $file,
        ]);

        // 5. Candidate Accepts Offer
        $response = $this->actingAs($candidate)->post(route('applications.offer.accept', $application));
        $response->assertRedirect();
        $this->assertEquals('accepted', $offer->fresh()->status);
        Notification::assertSentTo($admin, ApplicationStatusNotification::class);

        // 5. Candidate downloads Offer PDF
        $response = $this->actingAs($candidate)->get(route('applications.offer.download', $application));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_candidate_can_decline_offer(): void
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'selected',
        ]);

        Offer::create([
            'application_id' => $application->id,
            'salary' => 600000,
            'joining_date' => now()->addDays(15),
            'status' => 'sent',
        ]);

        $response = $this->actingAs($candidate)->post(route('applications.offer.decline', $application), [
            'decline_reason' => 'I have accepted another offer with immediate start date.',
        ]);
        $response->assertRedirect();
        $this->assertEquals('declined', $application->offer->fresh()->status);
        $this->assertEquals('I have accepted another offer with immediate start date.', $application->offer->fresh()->decline_reason);
        Notification::assertSentTo($admin, ApplicationStatusNotification::class);
    }

    public function test_expired_offer_cannot_be_accepted(): void
    {
        $candidate = $this->createCandidate();
        $job = $this->createJob();

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'selected',
        ]);

        Offer::create([
            'application_id' => $application->id,
            'salary' => 600000,
            'joining_date' => now()->addDays(15),
            'offer_expiry_date' => now()->subDay(), // Expired yesterday
            'status' => 'sent',
        ]);

        $response = $this->actingAs($candidate)->post(route('applications.offer.accept', $application));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'This offer has expired.');
        $this->assertEquals('sent', $application->offer->fresh()->status);
    }

    /*
    |--------------------------------------------------------------------------
    | 7. Profile & Resume Management
    |--------------------------------------------------------------------------
    */

    public function test_candidate_can_update_profile_and_manage_resumes(): void
    {
        Storage::fake('public');
        $candidate = $this->createCandidate();

        // Update Profile
        $response = $this->actingAs($candidate)->put(route('profile.update'), [
            'name' => 'Jane Senior Developer',
            'email' => 'jane.new@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $candidate->id,
            'name' => 'Jane Senior Developer',
            'email' => 'jane.new@example.com',
        ]);

        // Upload Resume
        $file = UploadedFile::fake()->create('my_resume.pdf', 200, 'application/pdf');
        $response = $this->actingAs($candidate)->post(route('profile.resume.upload'), [
            'resume' => $file,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('resumes', [
            'user_id' => $candidate->id,
            'file_name' => 'my_resume.pdf',
        ]);

        $resume = Resume::where('user_id', $candidate->id)->first();
        Storage::disk('public')->assertExists($resume->file_path);

        // Delete Resume
        $response = $this->actingAs($candidate)->delete(route('profile.resume.delete', $resume->id));
        $response->assertRedirect();
        $this->assertDatabaseMissing('resumes', ['id' => $resume->id]);
        Storage::disk('public')->assertMissing($resume->file_path);
    }
}
