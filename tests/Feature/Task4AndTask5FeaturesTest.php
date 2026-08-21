<?php

namespace Tests\Feature;

use App\Mail\CandidateAccountCreated;
use App\Mail\InterviewCancelled;
use App\Mail\InterviewRescheduled;
use App\Mail\InterviewScheduled;
use App\Mail\JobApplied;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Task4AndTask5FeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Mail::fake();
    }

    /**
     * Helper to create users by role.
     */
    protected function makeUser(string $role, array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => $role,
            'password' => Hash::make('password123'),
        ], $attributes));
    }

    /**
     * Helper to create a job opening.
     */
    protected function makeJob(array $attributes = []): Job
    {
        return Job::create(array_merge([
            'title' => 'Senior Backend Engineer',
            'company' => 'Apex Cloud Systems',
            'location' => 'Bangalore, India',
            'job_type' => 'Full-time',
            'description' => 'Build high-throughput distributed APIs in PHP, Laravel, Docker, and MySQL.',
            'requirements' => '5+ years experience in Laravel and MySQL architecture.',
            'skills' => ['PHP', 'Laravel', 'Docker', 'MySQL', 'Redis'],
            'salary' => '$120,000 - $140,000',
            'technical_interview_required' => true,
        ], $attributes));
    }

    public function test_public_homepage_is_accessible_without_authentication()
    {
        $this->makeJob(['title' => 'Lead DevOps Architect']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Build What&#039;s Next', false);
        $response->assertSee('Advait');
        $response->assertSee('Latest Opportunities');
        $response->assertSee('Lead DevOps Architect');
        $response->assertSee('Why Advait?');
        $response->assertSee('About Advait');
        $response->assertSee('How We Hire');
    }

    public function test_resume_analyzer_page_and_keyword_processing()
    {
        $this->makeJob([
            'title' => 'Full Stack Laravel Developer',
            'skills' => ['PHP', 'Laravel', 'React', 'MySQL', 'Docker'],
        ]);

        $response = $this->get(route('resume-analyzer.index'));
        $response->assertStatus(200);
        $response->assertSee('Resume Analyzer');
        $response->assertDontSee('Compare Against Specific Role');

        // Create dummy resume text file with technical skills
        $resumeContent = "John Doe\nExperienced Web Engineer\nSkills: PHP, Laravel, React, MySQL, Git, TailwindCSS, AWS.";
        $file = UploadedFile::fake()->createWithContent('resume.txt', $resumeContent);

        $analyzeResponse = $this->post(route('resume-analyzer.analyze'), [
            'resume' => $file,
        ]);

        $analyzeResponse->assertStatus(200);
        $analyzeResponse->assertSee('Your Resume Match');
        $analyzeResponse->assertSee('Full Stack Laravel Developer');
        $analyzeResponse->assertSee('PHP');
        $analyzeResponse->assertSee('Laravel');
        $analyzeResponse->assertSee('React');
        $analyzeResponse->assertSee('Apply Now');
    }

    public function test_resume_analyzer_handles_unreadable_file_gracefully()
    {
        $file = UploadedFile::fake()->createWithContent('empty.pdf', "%PDF-1.4 empty stream");

        $response = $this->post(route('resume-analyzer.analyze'), [
            'resume' => $file,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_first_time_candidate_applies_and_account_is_auto_created_with_password_email()
    {
        $job = $this->makeJob();
        $resumeFile = UploadedFile::fake()->create('john_resume.pdf', 500, 'application/pdf');

        $response = $this->post(route('applications.store', $job), [
            'name' => 'John Candidate',
            'email' => 'john.candidate@example.com',
            'resume' => $resumeFile,
            'cover_letter' => 'Excited to apply for this backend position.',
        ]);

        $response->assertRedirect(route('applications.index'));

        // Assert User is created in DB
        $user = User::where('email', 'john.candidate@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('John Candidate', $user->name);
        $this->assertEquals('user', $user->role);

        // Assert user is authenticated
        $this->assertAuthenticatedAs($user);

        // Assert Application exists
        $application = Application::where('user_id', $user->id)->where('job_id', $job->id)->first();
        $this->assertNotNull($application);
        $this->assertEquals('pending', $application->status);

        // Assert Welcome Email with Credentials was sent
        Mail::assertSent(CandidateAccountCreated::class, function ($mail) use ($user) {
            return $mail->user->id === $user->id && !empty($mail->temporaryPassword);
        });

        // Assert JobApplied email was sent
        Mail::assertSent(JobApplied::class, function ($mail) use ($application) {
            return $mail->application->id === $application->id;
        });
    }

    public function test_returning_candidate_applying_with_existing_email_is_prompted_to_login()
    {
        $job = $this->makeJob();
        $existingUser = $this->makeUser('user', ['email' => 'existing@example.com']);
        $resumeFile = UploadedFile::fake()->create('resume.pdf', 500, 'application/pdf');

        $response = $this->post(route('applications.store', $job), [
            'name' => 'Existing User',
            'email' => 'existing@example.com',
            'resume' => $resumeFile,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('warning');

        // Verify no duplicate user was created
        $this->assertEquals(1, User::where('email', 'existing@example.com')->count());
    }

    public function test_returning_candidate_can_login_with_credentials()
    {
        $candidate = $this->makeUser('user', [
            'email' => 'returning@example.com',
            'password' => Hash::make('secretPassword123'),
        ]);

        $response = $this->post(route('login.store'), [
            'email' => 'returning@example.com',
            'password' => 'secretPassword123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($candidate);
    }

    public function test_hr_dashboard_displays_scoped_today_and_upcoming_interviews()
    {
        $hrUser = $this->makeUser('hr', ['name' => 'Priya HR']);
        $otherHrUser = $this->makeUser('hr', ['name' => 'Kavita HR']);
        $candidate = $this->makeUser('user');
        $job = $this->makeJob();

        $app = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'interview',
        ]);

        // HR interview assigned to Priya HR today
        $todayInterview = Interview::create([
            'application_id' => $app->id,
            'type' => 'hr',
            'interviewer_id' => $hrUser->id,
            'interview_date' => today()->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'meeting_link' => 'https://meet.google.com/pri-ya-meet',
            'status' => 'scheduled',
        ]);

        // Interview assigned to other HR
        Interview::create([
            'application_id' => $app->id,
            'type' => 'hr',
            'interviewer_id' => $otherHrUser->id,
            'interview_date' => today()->toDateString(),
            'start_time' => '14:00:00',
            'end_time' => '15:00:00',
            'meeting_link' => 'https://meet.google.com/other-hr-meet',
            'status' => 'scheduled',
        ]);

        $this->actingAs($hrUser);

        $response = $this->get(route('hr.dashboard'));

        $response->assertStatus(200);
        $response->assertSeeText("Today's Scheduled HR Interviews");
        $response->assertSee('https://meet.google.com/pri-ya-meet');
        $response->assertDontSee('https://meet.google.com/other-hr-meet');
    }

    public function test_tr_dashboard_and_pipeline_displays_scoped_technical_candidates()
    {
        $trUser = $this->makeUser('tr', ['name' => 'Rahul TR']);
        $candidate = $this->makeUser('user', ['name' => 'Arun Tech Candidate']);
        $job = $this->makeJob(['technical_interview_required' => true]);

        $app = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'technical_interview',
        ]);

        $interview = Interview::create([
            'application_id' => $app->id,
            'type' => 'technical',
            'interviewer_id' => $trUser->id,
            'interview_date' => today()->toDateString(),
            'start_time' => '15:00:00',
            'end_time' => '16:00:00',
            'meeting_link' => 'https://meet.google.com/rah-ul-meet',
            'status' => 'scheduled',
        ]);

        $this->actingAs($trUser);

        $dashResponse = $this->get(route('tr.dashboard'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSeeText("Today's Scheduled Technical Rounds");
        $dashResponse->assertSee('https://meet.google.com/rah-ul-meet');

        $pipeResponse = $this->get(route('tr.applications.index'));
        $pipeResponse->assertStatus(200);
        $pipeResponse->assertSee('Technical Candidate Pipeline');
        $pipeResponse->assertSee('Arun Tech Candidate');
    }

    public function test_profile_endpoints_for_admin_hr_and_tr()
    {
        $admin = $this->makeUser('admin');
        $hr = $this->makeUser('hr');
        $tr = $this->makeUser('tr');
        $candidate = $this->makeUser('user');

        // Admin profile
        $this->actingAs($admin);
        $this->get(route('admin.profile'))->assertStatus(200)->assertSee('Administrator Profile');

        // HR profile
        $this->actingAs($hr);
        $this->get(route('hr.profile'))->assertStatus(200)->assertSee('HR Specialist Profile');

        // TR profile
        $this->actingAs($tr);
        $this->get(route('tr.profile'))->assertStatus(200)->assertSee('Technical Recruiter Profile');

        // Candidate forbidden from admin profile
        $this->actingAs($candidate);
        $this->get(route('admin.profile'))->assertStatus(403);
    }

    public function test_interview_scheduled_mailable_contains_all_interview_details()
    {
        $candidate = $this->makeUser('user', ['name' => 'Abhimanyu Prajapati']);
        $hrUser = $this->makeUser('hr', ['name' => 'Priya Sharma']);
        $job = $this->makeJob(['title' => 'Principal Backend Engineer', 'company' => 'Acme Corp']);

        $app = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'interview',
        ]);

        $interview = Interview::create([
            'application_id' => $app->id,
            'type' => 'hr',
            'interviewer_id' => $hrUser->id,
            'interview_date' => '2026-08-25',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'meeting_link' => 'https://meet.google.com/abc-defg-hij',
            'notes' => 'Please join 5-10 minutes early.',
            'status' => 'scheduled',
        ]);

        $mailable = new InterviewScheduled($interview);
        $rendered = $mailable->render();

        $this->assertStringContainsString('HR Interview Scheduled', $mailable->subject);
        $this->assertStringContainsString('Abhimanyu Prajapati', $rendered);
        $this->assertStringContainsString('Principal Backend Engineer', $rendered);
        $this->assertStringContainsString('Priya Sharma', $rendered);
        $this->assertStringContainsString('https://meet.google.com/abc-defg-hij', $rendered);
        $this->assertStringContainsString('Please join 5-10 minutes early.', $rendered);
    }
}
