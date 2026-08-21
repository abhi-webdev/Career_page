<?php

namespace Tests\Feature;

use App\Mail\OfferRevised;
use App\Mail\OfferSent;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Offer;
use App\Models\OfferVersion;
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

class RecruitmentWorkflowExtendedTest extends TestCase
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
            'company' => 'TechCorp Global',
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
    | TEST 1: Admin Completes Interview with Feedback Note & Attachment
    |--------------------------------------------------------------------------
    */

    public function test_admin_completes_interview_with_feedback_note_and_attachment_and_candidate_sees_it(): void
    {
        Notification::fake();
        Storage::fake('public');

        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();

        $application = Application::create([
            'user_id' => $candidate->id,
            'job_id' => $job->id,
            'status' => 'interview',
        ]);

        $interview = Interview::create([
            'application_id' => $application->id,
            'interview_date' => now()->subDay(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/test-meet',
            'status' => 'scheduled',
        ]);

        $feedbackText = 'Candidate displayed strong Laravel architecture and SQL optimization skills. Recommended for hire.';
        $attachment = UploadedFile::fake()->create('interview_evaluation.pdf', 200, 'application/pdf');

        // 1. Admin completes interview and submits feedback note + attachment
        $response = $this->actingAs($admin)->patch(route('admin.applications.interview.complete', $application), [
            'admin_feedback' => $feedbackText,
            'feedback_attachment' => $attachment,
        ]);

        $response->assertRedirect();
        $this->assertEquals('completed', $interview->fresh()->status);
        $this->assertEquals($feedbackText, $interview->fresh()->admin_feedback);
        $this->assertNotNull($interview->fresh()->feedback_attachment_path);
        $this->assertNotNull($interview->fresh()->feedback_submitted_at);

        Storage::disk('public')->assertExists($interview->fresh()->feedback_attachment_path);
        Notification::assertSentTo($candidate, ApplicationStatusNotification::class);

        // 2. Admin can download evaluation attachment
        $downloadResponse = $this->actingAs($admin)->get(route('admin.applications.interview.download-attachment', $application));
        $downloadResponse->assertStatus(200);

        // 3. Admin sees evaluation note on review page
        $adminView = $this->actingAs($admin)->get(route('admin.applications.show', $application));
        $adminView->assertStatus(200);
        $adminView->assertSee('Admin Feedback Note');
        $adminView->assertSee($feedbackText);

        // 4. Candidate views applications page and sees completed status and interviewer assessment
        $candidateView = $this->actingAs($candidate)->get(route('applications.index'));
        $candidateView->assertStatus(200);
        $candidateView->assertSee('Interview round completed');
        $candidateView->assertSee($feedbackText);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 2: Offer Sent, Email & Signing Note Display
    |--------------------------------------------------------------------------
    */

    public function test_admin_sends_offer_candidate_receives_email_and_sees_signing_notice(): void
    {
        Mail::fake();
        Notification::fake();
        Storage::fake('public');

        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        Interview::create([
            'application_id' => $application->id,
            'interview_date' => now()->subDays(2),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/test',
            'status' => 'completed',
        ]);

        // Admin creates draft
        $this->actingAs($admin)->post(route('admin.applications.offer.store', $application), [
            'salary' => 720000,
            'joining_date' => now()->addDays(20)->format('Y-m-d'),
            'offer_expiry_date' => now()->addDays(7)->format('Y-m-d'),
            'notes' => 'Health insurance and remote setup allowance.',
        ]);

        // Admin generates PDF
        $this->actingAs($admin)->post(route('admin.applications.offer.generate-letter', $application));

        // Admin sends offer
        $response = $this->actingAs($admin)->post(route('admin.applications.offer.send', $application));
        $response->assertRedirect();
        $this->assertEquals('sent', $application->offer->fresh()->status);

        Mail::assertSent(OfferSent::class);
        Notification::assertSentTo($candidate, ApplicationStatusNotification::class);

        // Candidate views Offer Hub and sees signing requirement
        $candidateView = $this->actingAs($candidate)->get(route('offers.current'));
        $candidateView->assertStatus(200);
        $candidateView->assertSee('Please download the offer letter, sign it, and upload the signed copy in the response section.');
        $candidateView->assertSee('Download Offer Letter');
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 3: Signed Offer Letter PDF Upload & Verification
    |--------------------------------------------------------------------------
    */

    public function test_candidate_uploads_signed_pdf_and_admin_can_download_it(): void
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

        // Candidate uploads signed PDF
        $file = UploadedFile::fake()->create('signed_offer_letter.pdf', 300, 'application/pdf');

        $response = $this->actingAs($candidate)->post(route('applications.offer.upload-signed', $application), [
            'signed_offer' => $file,
        ]);

        $response->assertRedirect();
        $this->assertNotNull($offer->fresh()->signed_offer_letter_path);
        $this->assertNotNull($offer->fresh()->signed_at);
        Storage::disk('public')->assertExists($offer->fresh()->signed_offer_letter_path);

        // Candidate sees upload status
        $candidateView = $this->actingAs($candidate)->get(route('offers.current'));
        $candidateView->assertSee('Signed offer uploaded');

        // Admin downloads signed document
        $adminDownload = $this->actingAs($admin)->get(route('admin.applications.offer.download-signed', $application));
        $adminDownload->assertStatus(200);
        $adminDownload->assertHeader('content-type', 'application/pdf');
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 4: Decline Offer Requires Mandatory Reason
    |--------------------------------------------------------------------------
    */

    public function test_candidate_decline_offer_requires_reason(): void
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 650000,
            'joining_date' => now()->addDays(20),
            'status' => 'sent',
        ]);

        // Attempt to decline without reason should fail validation
        $emptyDecline = $this->actingAs($candidate)->post(route('applications.offer.decline', $application), []);
        $emptyDecline->assertSessionHasErrors('decline_reason');
        $this->assertEquals('sent', $offer->fresh()->status);

        // Decline with mandatory reason
        $declineReason = 'I have accepted a lead position at another organization.';
        $response = $this->actingAs($candidate)->post(route('applications.offer.decline', $application), [
            'decline_reason' => $declineReason,
        ]);

        $response->assertRedirect();
        $this->assertEquals('declined', $offer->fresh()->status);
        $this->assertEquals($declineReason, $offer->fresh()->decline_reason);
        $this->assertNotNull($offer->fresh()->declined_at);

        Notification::assertSentTo($admin, ApplicationStatusNotification::class);

        // Admin can see decline reason
        $adminView = $this->actingAs($admin)->get(route('admin.applications.show', $application));
        $adminView->assertSee($declineReason);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 5: Candidate Requests Joining Date Change
    |--------------------------------------------------------------------------
    */

    public function test_candidate_requests_joining_date_change(): void
    {
        Notification::fake();
        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 700000,
            'joining_date' => '2026-08-21',
            'status' => 'sent',
        ]);

        $requestedDate = '2026-08-31';
        $reason = 'I have an examination and cannot join on the original date.';

        $response = $this->actingAs($candidate)->post(route('applications.offer.request-joining-date', $application), [
            'requested_joining_date' => $requestedDate,
            'joining_date_note' => $reason,
        ]);

        $response->assertRedirect();
        $this->assertEquals('pending', $offer->fresh()->joining_date_request_status);
        $this->assertEquals($requestedDate, $offer->fresh()->requested_joining_date->format('Y-m-d'));
        $this->assertEquals($reason, $offer->fresh()->joining_date_note);

        Notification::assertSentTo($admin, ApplicationStatusNotification::class);

        // Admin sees request in portal
        $adminView = $this->actingAs($admin)->get(route('admin.applications.show', $application));
        $adminView->assertSee('Joining Date Change Request');
        $adminView->assertSee($reason);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 6: Admin Revises Offer (Versioning, Audit, Email Notification)
    |--------------------------------------------------------------------------
    */

    public function test_admin_approves_date_change_and_generates_revised_offer_version(): void
    {
        Mail::fake();
        Notification::fake();
        Storage::fake('public');

        $admin = $this->createAdmin();
        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 1,
            'salary' => 750000,
            'joining_date' => '2026-08-21',
            'offer_letter_path' => 'offers/v1.pdf',
            'status' => 'sent',
            'requested_joining_date' => '2026-08-31',
            'joining_date_note' => 'College exams until 28 August.',
            'joining_date_request_status' => 'pending',
        ]);
        Storage::disk('public')->put('offers/v1.pdf', 'V1 PDF Content');
        $offer->snapshotVersion(1, 'sent');

        // Admin generates revised offer (Version 2)
        $newJoiningDate = '2026-08-31';
        $response = $this->actingAs($admin)->post(route('admin.applications.offer.revise', $application), [
            'joining_date' => $newJoiningDate,
            'salary' => 750000,
        ]);

        $response->assertRedirect();
        $updatedOffer = $offer->fresh();

        // Verify version 2 created and active
        $this->assertEquals(2, $updatedOffer->version);
        $this->assertEquals($newJoiningDate, $updatedOffer->joining_date->format('Y-m-d'));
        $this->assertEquals('sent', $updatedOffer->status);
        $this->assertEquals('approved', $updatedOffer->joining_date_request_status);

        // Verify old Version 1 is preserved in offer_versions table
        $this->assertDatabaseHas('offer_versions', [
            'offer_id' => $offer->id,
            'version' => 1,
        ]);
        $this->assertEquals('2026-08-21', OfferVersion::where('offer_id', $offer->id)->where('version', 1)->first()->joining_date->format('Y-m-d'));

        // Verify Version 2 is also in offer_versions table
        $this->assertDatabaseHas('offer_versions', [
            'offer_id' => $offer->id,
            'version' => 2,
        ]);
        $this->assertEquals('2026-08-31', OfferVersion::where('offer_id', $offer->id)->where('version', 2)->first()->joining_date->format('Y-m-d'));

        // Candidate notified via DB & Email
        Mail::assertSent(OfferRevised::class);
        Notification::assertSentTo($candidate, ApplicationStatusNotification::class);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 7: Candidate Responds to Revised Offer & Multi-Version Support
    |--------------------------------------------------------------------------
    */

    public function test_candidate_responds_to_revised_offer_and_can_accept_or_request_further_revisions(): void
    {
        Notification::fake();
        Storage::fake('public');

        $candidate = $this->createCandidate();
        $job = $this->createJob();
        $application = $this->createSelectedApplication($candidate, $job);

        $offer = Offer::create([
            'application_id' => $application->id,
            'version' => 2,
            'salary' => 850000,
            'joining_date' => '2026-09-01',
            'offer_letter_path' => 'offers/v2.pdf',
            'signed_offer_letter_path' => 'signed_offers/v2_signed.pdf',
            'status' => 'sent',
        ]);
        Storage::disk('public')->put('offers/v2.pdf', 'V2 PDF Content');
        Storage::disk('public')->put('signed_offers/v2_signed.pdf', 'Signed PDF Content');

        // Candidate views revised offer
        $candidateView = $this->actingAs($candidate)->get(route('offers.current'));
        $candidateView->assertStatus(200);
        $candidateView->assertSee('Revised Offer');
        $candidateView->assertSee('Version 2');

        // Candidate accepts revised offer
        $acceptResponse = $this->actingAs($candidate)->post(route('applications.offer.accept', $application));
        $acceptResponse->assertRedirect();
        $this->assertEquals('accepted', $offer->fresh()->status);
    }

    /*
    |--------------------------------------------------------------------------
    | TEST 8: Strict Security & Cross-Candidate Isolation
    |--------------------------------------------------------------------------
    */

    public function test_candidate_a_cannot_access_or_manipulate_candidate_b_resources(): void
    {
        $candidateA = $this->createCandidate(['email' => 'candidateA@example.com']);
        $candidateB = $this->createCandidate(['email' => 'candidateB@example.com']);
        $job = $this->createJob();

        $applicationB = $this->createSelectedApplication($candidateB, $job);
        $interviewB = Interview::create([
            'application_id' => $applicationB->id,
            'interview_date' => now()->subDay(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'meeting_link' => 'https://meet.google.com/test',
            'status' => 'completed',
        ]);
        $offerB = Offer::create([
            'application_id' => $applicationB->id,
            'version' => 1,
            'salary' => 900000,
            'joining_date' => now()->addDays(15),
            'status' => 'sent',
        ]);

        // Candidate A attempts to view Candidate B's offer
        $this->actingAs($candidateA)->get(route('applications.offer.show', $applicationB))
            ->assertStatus(403);

        // Candidate A attempts to upload signed offer for Candidate B
        $file = UploadedFile::fake()->create('fake_signed.pdf', 100, 'application/pdf');
        $this->actingAs($candidateA)->post(route('applications.offer.upload-signed', $applicationB), [
            'signed_offer' => $file,
        ])->assertStatus(403);

        // Candidate A attempts to request joining date for Candidate B
        $this->actingAs($candidateA)->post(route('applications.offer.request-joining-date', $applicationB), [
            'requested_joining_date' => now()->addDays(20)->format('Y-m-d'),
            'joining_date_note' => 'Malicious request',
        ])->assertStatus(403);

        // Candidate A attempts to accept Candidate B's offer
        $this->actingAs($candidateA)->post(route('applications.offer.accept', $applicationB))
            ->assertStatus(403);

        // Candidate A attempts to decline Candidate B's offer
        $this->actingAs($candidateA)->post(route('applications.offer.decline', $applicationB), [
            'decline_reason' => 'Unauthorized decline',
        ])->assertStatus(403);

        // Candidate A attempts to download Candidate B's signed document
        $this->actingAs($candidateA)->get(route('applications.offer.download-signed', $applicationB))
            ->assertStatus(403);
    }
}
