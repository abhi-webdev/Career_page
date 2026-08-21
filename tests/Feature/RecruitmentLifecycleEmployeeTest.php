<?php

namespace Tests\Feature;

use App\Mail\OfferAccepted;
use App\Models\Application;
use App\Models\Employee;
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

class RecruitmentLifecycleEmployeeTest extends TestCase
{
    use RefreshDatabase;

    protected function createCandidate(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Abhimanyu Prajapati',
            'email' => 'candidate' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ], $attributes));
    }

    protected function createAdmin(array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'Recruitment Admin',
            'email' => 'admin' . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ], $attributes));
    }

    protected function createJob(array $attributes = []): Job
    {
        return Job::create(array_merge([
            'title' => 'Backend Developer',
            'company' => 'Advait Business Solution',
            'description' => 'We are seeking a senior backend developer.',
            'skills' => ['PHP', 'Laravel', 'MySQL'],
            'location' => 'Remote',
            'job_type' => 'Full Time',
            'experience' => '3-5 years',
            'application_deadline' => now()->addDays(30),
        ], $attributes));
    }

    protected function createSelectedApplication(User $candidate, Job $job): Application
    {
        $resume = Resume::create([
            'user_id' => $candidate->id,
            'file_name' => 'resume.pdf',
            'file_path' => 'resumes/resume.pdf',
        ]);

        return Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'selected',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 1: Candidate receives offer
    |--------------------------------------------------------------------------
    */
    public function test_candidate_receives_offer(): void
    {
        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 850000,
            'joining_date' => now()->addDays(20),
            'status' => 'sent',
            'offer_letter_path' => 'offers/offer.pdf',
        ]);

        $response = $this->actingAs($candidate)->get(route('offers.current'));
        $response->assertStatus(200);
        $response->assertSee('Employment Offer for ' . $job->title);
        $response->assertSee('₹850,000.00');
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 2: Candidate uploads signed offer
    |--------------------------------------------------------------------------
    */
    public function test_candidate_uploads_signed_offer(): void
    {
        Notification::fake();
        Storage::fake('public');

        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 800000,
            'joining_date' => now()->addDays(25),
            'status' => 'sent',
        ]);

        $file = UploadedFile::fake()->create('signed_offer.pdf', 200, 'application/pdf');

        $response = $this->actingAs($candidate)->post(route('applications.offer.upload-signed', $application), [
            'signed_offer' => $file,
        ]);

        $response->assertRedirect();
        $this->assertNotNull($offer->fresh()->signed_offer_letter_path);
        $this->assertNotNull($offer->fresh()->signed_at);
        Storage::disk('public')->assertExists($offer->fresh()->signed_offer_letter_path);

        // Admin can download signed letter
        $adminDownload = $this->actingAs($admin)->get(route('admin.applications.offer.download-signed', $application));
        $adminDownload->assertStatus(200);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 3: Candidate attempts to accept without signed offer -> Blocked
    |--------------------------------------------------------------------------
    */
    public function test_candidate_cannot_accept_offer_without_signed_offer_letter(): void
    {
        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 750000,
            'joining_date' => now()->addDays(15),
            'status' => 'sent',
            'signed_offer_letter_path' => null,
        ]);

        $response = $this->actingAs($candidate)->post(route('applications.offer.accept', $application));
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Please upload the signed offer letter before accepting the offer.');

        $this->assertEquals('sent', $offer->fresh()->status);
        $this->assertDatabaseMissing('employees', [
            'application_id' => $application->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 4 & 5: Candidate accepts after signed offer upload -> Offer accepted & Employee created
    |--------------------------------------------------------------------------
    */
    public function test_candidate_accepts_after_signed_offer_upload_and_employee_is_created(): void
    {
        Mail::fake();
        Notification::fake();
        Storage::fake('public');

        $admin = $this->createAdmin();
        $candidate = $this->createCandidate(['name' => 'Abhi Prajapati']);
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $joiningDate = '2026-08-31';
        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 900000,
            'joining_date' => $joiningDate,
            'status' => 'sent',
            'signed_offer_letter_path' => 'signed_offers/signed.pdf',
            'signed_at' => now(),
        ]);
        Storage::disk('public')->put('signed_offers/signed.pdf', 'PDF content');

        $response = $this->actingAs($candidate)->post(route('applications.offer.accept', $application));
        $response->assertRedirect();

        // Verify offer status accepted
        $this->assertEquals('accepted', $offer->fresh()->status);

        // Verify Employee automatically created
        $this->assertDatabaseHas('employees', [
            'application_id' => $application->id,
            'user_id' => $candidate->id,
            'offer_id' => $offer->id,
            'status' => 'pending',
        ]);

        $employee = Employee::where('application_id', $application->id)->first();
        $this->assertNotNull($employee);
        $this->assertEquals('EMP-0001', $employee->employee_code);
        $this->assertEquals($joiningDate, $employee->joining_date->format('Y-m-d'));

        // Mail & Notifications sent
        Mail::assertSent(OfferAccepted::class);
        Notification::assertSentTo($candidate, ApplicationStatusNotification::class);
        Notification::assertSentTo($admin, ApplicationStatusNotification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 6: Candidate submits accept request twice -> Idempotent, no duplicate
    |--------------------------------------------------------------------------
    */
    public function test_candidate_submits_accept_twice_no_duplicate_employee(): void
    {
        Notification::fake();
        Storage::fake('public');

        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 800000,
            'joining_date' => '2026-08-31',
            'status' => 'sent',
            'signed_offer_letter_path' => 'signed_offers/signed.pdf',
            'signed_at' => now(),
        ]);
        Storage::disk('public')->put('signed_offers/signed.pdf', 'PDF content');

        // First accept
        $this->actingAs($candidate)->post(route('applications.offer.accept', $application));
        $this->assertEquals(1, Employee::where('application_id', $application->id)->count());

        // Second accept attempt
        $second = $this->actingAs($candidate)->post(route('applications.offer.accept', $application));
        $second->assertSessionHas('error');
        $this->assertEquals(1, Employee::where('application_id', $application->id)->count());
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 7: Admin candidate profile hides unnecessary mutating actions
    |--------------------------------------------------------------------------
    */
    public function test_admin_candidate_profile_hides_mutating_actions_when_hired(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $candidate = $this->createCandidate(['name' => 'Abhi Prajapati']);
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 850000,
            'joining_date' => '2026-08-31',
            'status' => 'accepted',
            'offer_letter_path' => 'offers/offer.pdf',
            'signed_offer_letter_path' => 'signed_offers/signed.pdf',
            'signed_at' => now(),
        ]);
        Storage::disk('public')->put('signed_offers/signed.pdf', 'PDF content');
        Storage::disk('public')->put('offers/offer.pdf', 'PDF content');

        $employee = Employee::create([
            'application_id' => $application->id,
            'user_id' => $candidate->id,
            'offer_id' => $offer->id,
            'employee_code' => 'EMP-0001',
            'joining_date' => '2026-08-31',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.applications.show', $application));
        $response->assertStatus(200);

        // Actions hidden
        $response->assertDontSee('Generate Revised Offer');
        $response->assertDontSee('Send Offer to Candidate');

        // Information visible
        $response->assertSee('EMP-0001');
        $response->assertSee('Candidate Hired');
        $response->assertSee('Signed Offer Letter');
        $response->assertSee('Download Signed Offer Letter');
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 8: Admin Employees page and status update
    |--------------------------------------------------------------------------
    */
    public function test_admin_employees_page_and_status_update(): void
    {
        Storage::fake('public');
        $admin = $this->createAdmin();
        $candidate = $this->createCandidate(['name' => 'Abhi Prajapati']);
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 900000,
            'joining_date' => '2026-08-31',
            'status' => 'accepted',
            'signed_offer_letter_path' => 'signed_offers/signed.pdf',
        ]);
        Storage::disk('public')->put('signed_offers/signed.pdf', 'PDF content');

        $employee = Employee::create([
            'application_id' => $application->id,
            'user_id' => $candidate->id,
            'offer_id' => $offer->id,
            'employee_code' => 'EMP-0001',
            'joining_date' => '2026-08-31',
            'status' => 'pending',
        ]);

        // 1. Appears in index
        $indexView = $this->actingAs($admin)->get(route('admin.employees.index'));
        $indexView->assertStatus(200);
        $indexView->assertSee('EMP-0001');
        $indexView->assertSee('Abhi Prajapati');

        // 2. View details
        $showView = $this->actingAs($admin)->get(route('admin.employees.show', $employee));
        $showView->assertStatus(200);
        $showView->assertSee('EMP-0001');
        $showView->assertSee('Employment Information');

        // 3. Admin can download signed offer from employee endpoint
        $download = $this->actingAs($admin)->get(route('admin.employees.signed-offer', $employee));
        $download->assertStatus(200);

        // 4. Update status to active
        $update = $this->actingAs($admin)->patch(route('admin.employees.status', $employee), [
            'status' => 'active',
        ]);
        $update->assertRedirect();
        $this->assertEquals('active', $employee->fresh()->status);
        $this->assertNotNull($employee->fresh()->joined_at);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 9: Employee joining date comes from FINAL accepted offer version (V2)
    |--------------------------------------------------------------------------
    */
    public function test_employee_joining_date_comes_from_final_accepted_offer_version(): void
    {
        Notification::fake();
        Storage::fake('public');

        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        // Version 2 Offer with revised date
        $finalJoiningDate = '2026-09-15';
        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 2,
            'salary' => 950000,
            'joining_date' => $finalJoiningDate,
            'status' => 'sent',
            'signed_offer_letter_path' => 'signed_offers/v2_signed.pdf',
            'signed_at' => now(),
        ]);
        Storage::disk('public')->put('signed_offers/v2_signed.pdf', 'PDF content');

        $this->actingAs($candidate)->post(route('applications.offer.accept', $application));

        $employee = Employee::where('application_id', $application->id)->first();
        $this->assertNotNull($employee);
        $this->assertEquals($finalJoiningDate, $employee->joining_date->format('Y-m-d'));
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 10: Unauthorized candidate access to another candidate's employee/signed offer -> Blocked (403)
    |--------------------------------------------------------------------------
    */
    public function test_unauthorized_candidate_cannot_access_employee_endpoints(): void
    {
        $candidateA = $this->createCandidate(['email' => 'candidateA@example.com']);
        $candidateB = $this->createCandidate(['email' => 'candidateB@example.com']);
        $job = $this->createJob();

        $applicationB = $this->createSelectedApplication($candidateB, $job);
        $offerB = Offer::create([
            'application_id' => $applicationB->id,
            'version' => 1,
            'salary' => 800000,
            'joining_date' => '2026-08-31',
            'status' => 'accepted',
        ]);
        $employeeB = Employee::create([
            'application_id' => $applicationB->id,
            'user_id' => $candidateB->id,
            'offer_id' => $offerB->id,
            'employee_code' => 'EMP-0002',
            'joining_date' => '2026-08-31',
            'status' => 'pending',
        ]);

        // Candidate A tries to access admin employee list
        $this->actingAs($candidateA)->get(route('admin.employees.index'))->assertStatus(403);

        // Candidate A tries to view employee B
        $this->actingAs($candidateA)->get(route('admin.employees.show', $employeeB))->assertStatus(403);

        // Candidate A tries to download employee B signed offer
        $this->actingAs($candidateA)->get(route('admin.employees.signed-offer', $employeeB))->assertStatus(403);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 11: Admin dashboard employee metrics & upcoming joinings
    |--------------------------------------------------------------------------
    */
    public function test_admin_dashboard_shows_employee_metrics_and_upcoming_joinings(): void
    {
        $admin = $this->createAdmin();
        $candidate = $this->createCandidate(['name' => 'Rahul Sharma']);
        $job = $this->createJob(['title' => 'Frontend Developer']);
        $application = $this->createSelectedApplication($candidate, $job);

        $futureJoiningDate = now()->addDays(10)->format('Y-m-d');
        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 800000,
            'joining_date' => $futureJoiningDate,
            'status' => 'accepted',
        ]);

        Employee::create([
            'application_id' => $application->id,
            'user_id' => $candidate->id,
            'offer_id' => $offer->id,
            'employee_code' => 'EMP-0001',
            'joining_date' => $futureJoiningDate,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Employee');
        $response->assertSee('Onboarding Metrics');
        $response->assertSee('Upcoming Joinings');
        $response->assertSee('Rahul Sharma');
        $response->assertSee('EMP-0001');
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 12: Candidate dashboard and applications list show Hired status
    |--------------------------------------------------------------------------
    */
    public function test_candidate_dashboard_and_applications_show_hired_status_and_employee_code(): void
    {
        Storage::fake('public');
        $candidate = $this->createCandidate(['name' => 'Abhi Prajapati']);
        $job = $this->createJob(['title' => 'Senior Backend Engineer', 'company' => 'Advait Tech']);
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 950000,
            'joining_date' => '2026-09-01',
            'status' => 'accepted',
            'signed_offer_letter_path' => 'signed_offers/signed.pdf',
        ]);
        Storage::disk('public')->put('signed_offers/signed.pdf', 'PDF content');

        Employee::create([
            'application_id' => $application->id,
            'user_id' => $candidate->id,
            'offer_id' => $offer->id,
            'employee_code' => 'EMP-0001',
            'joining_date' => '2026-09-01',
            'status' => 'pending',
        ]);

        // 1. Candidate Dashboard
        $dashboardResponse = $this->actingAs($candidate)->get(route('dashboard'));
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('EMP-0001');
        $dashboardResponse->assertSee('officially hired');
        $dashboardResponse->assertSee('Advait Tech');

        // 2. Candidate Applications Page
        $appsResponse = $this->actingAs($candidate)->get(route('applications.index'));
        $appsResponse->assertStatus(200);
        $appsResponse->assertSee('Hired');
        $appsResponse->assertSee('EMP-0001');
    }
}

