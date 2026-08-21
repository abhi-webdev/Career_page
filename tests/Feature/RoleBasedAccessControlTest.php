<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Employee;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Offer;
use App\Models\Resume;
use App\Models\RoleChangeLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected function createUserWithRole(string $role, array $attributes = []): User
    {
        return User::create(array_merge([
            'name' => 'User ' . strtoupper($role),
            'email' => strtolower($role) . uniqid() . '@example.com',
            'password' => Hash::make('password123'),
            'role' => $role,
        ], $attributes));
    }

    protected function createJob(): Job
    {
        return Job::create([
            'title' => 'Software Engineer',
            'company' => 'Advait Tech',
            'description' => 'Great role',
            'skills' => ['PHP', 'Laravel'],
            'location' => 'Remote',
            'job_type' => 'Full Time',
            'experience' => '3 years',
            'application_deadline' => now()->addDays(30),
        ]);
    }

    protected function createHiredEmployee(User $user, Job $job): Employee
    {
        $resume = Resume::create([
            'user_id' => $user->id,
            'file_name' => 'resume.pdf',
            'file_path' => 'resumes/resume.pdf',
        ]);

        $application = Application::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'selected',
        ]);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 850000,
            'joining_date' => '2026-09-01',
            'status' => 'accepted',
            'signed_offer_letter_path' => 'signed_offers/signed.pdf',
        ]);

        return Employee::create([
            'user_id' => $user->id,
            'application_id' => $application->id,
            'offer_id' => $offer->id,
            'employee_code' => 'EMP-0001',
            'joining_date' => '2026-09-01',
            'status' => 'pending',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 1. Route Boundary & Access Control Tests
    |--------------------------------------------------------------------------
    */

    public function test_candidate_cannot_access_admin_hr_or_tr_routes(): void
    {
        $candidate = $this->createUserWithRole('user');

        $this->actingAs($candidate)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($candidate)->get(route('hr.dashboard'))->assertStatus(403);
        $this->actingAs($candidate)->get(route('tr.dashboard'))->assertStatus(403);
    }

    public function test_employee_cannot_access_admin_hr_or_tr_routes(): void
    {
        $employeeUser = $this->createUserWithRole('employee');

        $this->actingAs($employeeUser)->get(route('admin.dashboard'))->assertStatus(403);
        $this->actingAs($employeeUser)->get(route('hr.dashboard'))->assertStatus(403);
        $this->actingAs($employeeUser)->get(route('tr.dashboard'))->assertStatus(403);
    }

    public function test_hr_cannot_access_admin_routes_or_role_assignment(): void
    {
        $hr = $this->createUserWithRole('hr');
        $job = $this->createJob();
        $employeeUser = $this->createUserWithRole('employee');
        $employee = $this->createHiredEmployee($employeeUser, $job);

        // HR accessing admin dashboard
        $this->actingAs($hr)->get(route('admin.dashboard'))->assertStatus(403);

        // HR attempting role assignment
        $this->actingAs($hr)->post(route('admin.employees.role.update', $employee), [
            'role' => 'admin',
        ])->assertStatus(403);

        // Verify role not modified
        $this->assertEquals('employee', $employeeUser->fresh()->role);
    }

    public function test_tr_cannot_access_admin_routes_or_role_assignment(): void
    {
        $tr = $this->createUserWithRole('tr');
        $job = $this->createJob();
        $employeeUser = $this->createUserWithRole('employee');
        $employee = $this->createHiredEmployee($employeeUser, $job);

        // TR accessing admin dashboard
        $this->actingAs($tr)->get(route('admin.dashboard'))->assertStatus(403);

        // TR attempting role assignment
        $this->actingAs($tr)->post(route('admin.employees.role.update', $employee), [
            'role' => 'admin',
        ])->assertStatus(403);

        // Verify role not modified
        $this->assertEquals('employee', $employeeUser->fresh()->role);
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Admin Role Assignment & Audit Trail Tests
    |--------------------------------------------------------------------------
    */

    public function test_admin_can_assign_employee_roles_and_creates_audit_log(): void
    {
        $admin = $this->createUserWithRole('admin', ['name' => 'Super Admin']);
        $job = $this->createJob();
        $staffUser = $this->createUserWithRole('employee', ['name' => 'Abhi Prajapati']);
        $employee = $this->createHiredEmployee($staffUser, $job);

        // 1. Promote employee -> HR
        $response1 = $this->actingAs($admin)->post(route('admin.employees.role.update', $employee), [
            'role' => 'hr',
        ]);
        $response1->assertRedirect();
        $this->assertEquals('hr', $staffUser->fresh()->role);
        $this->assertEquals('hr', $employee->fresh()->role);

        $this->assertDatabaseHas('role_change_logs', [
            'employee_id' => $employee->id,
            'changed_by' => $admin->id,
            'old_role' => 'employee',
            'new_role' => 'hr',
        ]);

        // 2. Change HR -> TR
        $response2 = $this->actingAs($admin)->post(route('admin.employees.role.update', $employee), [
            'role' => 'tr',
        ]);
        $response2->assertRedirect();
        $this->assertEquals('tr', $staffUser->fresh()->role);

        $this->assertDatabaseHas('role_change_logs', [
            'employee_id' => $employee->id,
            'changed_by' => $admin->id,
            'old_role' => 'hr',
            'new_role' => 'tr',
        ]);

        // 3. Promote TR -> Admin
        $response3 = $this->actingAs($admin)->post(route('admin.employees.role.update', $employee), [
            'role' => 'admin',
        ]);
        $response3->assertRedirect();
        $this->assertEquals('admin', $staffUser->fresh()->role);

        // 4. Demote Admin -> Employee
        $response4 = $this->actingAs($admin)->post(route('admin.employees.role.update', $employee), [
            'role' => 'employee',
        ]);
        $response4->assertRedirect();
        $this->assertEquals('employee', $staffUser->fresh()->role);

        $this->assertEquals(4, RoleChangeLog::where('employee_id', $employee->id)->count());
    }

    public function test_role_change_preserves_password_and_uses_single_account(): void
    {
        $admin = $this->createUserWithRole('admin');
        $job = $this->createJob();
        $password = 'secretPassword123!';
        $user = User::create([
            'name' => 'Staff Member',
            'email' => 'staff@example.com',
            'password' => Hash::make($password),
            'role' => 'employee',
        ]);
        $employee = $this->createHiredEmployee($user, $job);
        $initialPasswordHash = $user->password;

        // Admin assigns HR role
        $this->actingAs($admin)->post(route('admin.employees.role.update', $employee), [
            'role' => 'hr',
        ]);

        $user->refresh();
        $this->assertEquals('hr', $user->role);
        $this->assertEquals($initialPasswordHash, $user->password);
        $this->assertTrue(Hash::check($password, $user->password));

        // User can log in with same password and lands on HR portal
        auth()->logout();

        $this->post(route('login.store'), [
            'email' => 'staff@example.com',
            'password' => $password,
        ])->assertRedirect(route('hr.dashboard'));
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Role-Based Login & Dashboard Redirection
    |--------------------------------------------------------------------------
    */

    public function test_role_based_login_redirects(): void
    {
        $password = 'password123';

        // 1. Admin login -> admin.dashboard
        auth()->logout();
        $admin = User::create(['name' => 'Admin', 'email' => 'admin_test@example.com', 'password' => Hash::make($password), 'role' => 'admin']);
        $this->post(route('login.store'), ['email' => 'admin_test@example.com', 'password' => $password])
            ->assertRedirect(route('admin.dashboard'));

        // 2. HR login -> hr.dashboard
        auth()->logout();
        $hr = User::create(['name' => 'HR', 'email' => 'hr_test@example.com', 'password' => Hash::make($password), 'role' => 'hr']);
        $this->post(route('login.store'), ['email' => 'hr_test@example.com', 'password' => $password])
            ->assertRedirect(route('hr.dashboard'));

        // 3. TR login -> tr.dashboard
        auth()->logout();
        $tr = User::create(['name' => 'TR', 'email' => 'tr_test@example.com', 'password' => Hash::make($password), 'role' => 'tr']);
        $this->post(route('login.store'), ['email' => 'tr_test@example.com', 'password' => $password])
            ->assertRedirect(route('tr.dashboard'));

        // 4. Employee login -> employee.dashboard
        auth()->logout();
        $employee = User::create(['name' => 'Emp', 'email' => 'emp_test@example.com', 'password' => Hash::make($password), 'role' => 'employee']);
        $this->post(route('login.store'), ['email' => 'emp_test@example.com', 'password' => $password])
            ->assertRedirect(route('employee.dashboard'));

        // 5. Candidate login -> dashboard
        auth()->logout();
        $candidate = User::create(['name' => 'Cand', 'email' => 'cand_test@example.com', 'password' => Hash::make($password), 'role' => 'user']);
        $this->post(route('login.store'), ['email' => 'cand_test@example.com', 'password' => $password])
            ->assertRedirect(route('dashboard'));
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Automatic Role Transition on Offer Acceptance
    |--------------------------------------------------------------------------
    */

    public function test_candidate_role_automatically_transitions_to_employee_on_offer_accept(): void
    {
        Storage::fake('public');
        $candidate = $this->createUserWithRole('user');
        $job = $this->createJob();

        $resume = Resume::create([
            'user_id' => $candidate->id,
            'file_name' => 'resume.pdf',
            'file_path' => 'resumes/resume.pdf',
        ]);

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'resume_id' => $resume->id,
            'status' => 'selected',
        ]);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 900000,
            'joining_date' => '2026-09-01',
            'status' => 'sent',
            'signed_offer_letter_path' => 'signed_offers/signed.pdf',
        ]);
        Storage::disk('public')->put('signed_offers/signed.pdf', 'PDF content');

        $this->assertEquals('user', $candidate->role);

        // Candidate accepts offer
        $this->actingAs($candidate)->post(route('applications.offer.accept', $application));

        $candidate->refresh();
        $this->assertEquals('employee', $candidate->role);
        $this->assertDatabaseHas('employees', [
            'application_id' => $application->id,
            'user_id' => $candidate->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Privilege Escalation Hardening
    |--------------------------------------------------------------------------
    */

    public function test_registration_always_forces_user_role_even_if_tampered(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Hacker Candidate',
            'email' => 'hacker@example.com',
            'password' => 'password123',
            'role' => 'admin', // Tampered input
        ]);

        $response->assertRedirect();
        $created = User::where('email', 'hacker@example.com')->first();
        $this->assertNotNull($created);
        $this->assertEquals('user', $created->role);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. HR and TR Portal Functionality
    |--------------------------------------------------------------------------
    */

    public function test_hr_portal_views_and_status_update(): void
    {
        Storage::fake('public');
        $hr = $this->createUserWithRole('hr');
        $job = $this->createJob();
        $staffUser = $this->createUserWithRole('employee');
        $employee = $this->createHiredEmployee($staffUser, $job);
        Storage::disk('public')->put('signed_offers/signed.pdf', 'Signed PDF');

        // HR dashboard
        $this->actingAs($hr)->get(route('hr.dashboard'))->assertStatus(200)->assertSee('HR Overview');

        // HR employee directory
        $this->actingAs($hr)->get(route('hr.employees.index'))->assertStatus(200)->assertSee($employee->employee_code);

        // HR employee show
        $this->actingAs($hr)->get(route('hr.employees.show', $employee))->assertStatus(200);

        // HR download signed offer
        $this->actingAs($hr)->get(route('hr.employees.signed-offer', $employee))->assertStatus(200);

        // HR updates employee status
        $this->actingAs($hr)->patch(route('hr.employees.status', $employee), [
            'status' => 'active',
        ])->assertRedirect();
        $this->assertEquals('active', $employee->fresh()->status);
    }

    public function test_tr_portal_views_and_interview_completion(): void
    {
        $tr = $this->createUserWithRole('tr');
        $job = $this->createJob();
        $candidate = $this->createUserWithRole('user');

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'shortlisted',
        ]);

        // TR dashboard
        $this->actingAs($tr)->get(route('tr.dashboard'))->assertStatus(200)->assertSee('TR Overview');

        // TR schedules interview
        $this->actingAs($tr)->post(route('tr.applications.interview.store', $application), [
            'interview_date' => now()->addDays(2)->format('Y-m-d'),
            'start_time' => '11:00',
            'end_time' => '12:00',
            'meeting_link' => 'https://meet.google.com/abc-xyz',
            'notes' => 'PHP/Laravel deep dive',
        ])->assertRedirect();

        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'status' => 'scheduled',
        ]);

        // TR completes interview with feedback
        $this->actingAs($tr)->patch(route('tr.applications.interview.complete', $application), [
            'admin_feedback' => 'Candidate demonstrated strong architectural capabilities.',
        ])->assertRedirect();

        $this->assertDatabaseHas('interviews', [
            'application_id' => $application->id,
            'status' => 'completed',
            'admin_feedback' => 'Candidate demonstrated strong architectural capabilities.',
        ]);
    }
}
