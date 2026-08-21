<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OfferRevised;
use App\Mail\OfferSent;
use App\Models\Application;
use App\Models\Offer;
use App\Models\OfferVersion;
use App\Notifications\ApplicationStatusNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class OfferController extends Controller
{
    /**
     * Show offer creation form.
     */
    public function create(Application $application)
    {
        $application->load([
            'user',
            'job',
            'interview',
            'offer.versions',
        ]);

        if ($application->status !== 'selected') {
            return redirect()
                ->route('admin.applications.show', $application)
                ->with(
                    'error',
                    'An offer can only be created for a selected candidate.'
                );
        }

        if (
            !$application->interview ||
            $application->interview->status !== 'completed'
        ) {
            return redirect()
                ->route('admin.applications.show', $application)
                ->with(
                    'error',
                    'The interview must be completed before creating an offer.'
                );
        }

        return view(
            'admin.offers.create',
            compact('application')
        );
    }

    /**
     * Store offer.
     */
    public function store(
        Request $request,
        Application $application
    ) {
        if ($application->status !== 'selected') {
            return redirect()
                ->route('admin.applications.show', $application)
                ->with(
                    'error',
                    'Only selected candidates can receive an offer.'
                );
        }

        if (
            !$application->interview ||
            $application->interview->status !== 'completed'
        ) {
            return redirect()
                ->route('admin.applications.show', $application)
                ->with(
                    'error',
                    'The interview must be completed before creating an offer.'
                );
        }

        $validated = $request->validate([
            'salary' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999999',
            ],

            'joining_date' => [
                'required',
                'date',
            ],

            'offer_expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $offer = Offer::updateOrCreate(
            [
                'application_id' => $application->id,
            ],
            [
                'version' => 1,
                'salary' => $validated['salary'],
                'joining_date' => $validated['joining_date'],
                'offer_expiry_date' => $validated['offer_expiry_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'status' => 'draft',
            ]
        );

        $offer->snapshotVersion(1, 'draft');

        return redirect()
            ->route('admin.applications.show', $application)
            ->with(
                'success',
                'Offer saved as draft successfully.'
            );
    }

    /**
     * Send offer to candidate.
     */
    public function send(Application $application)
    {
        $application->load([
            'user',
            'job',
            'interview',
            'offer',
        ]);

        if (!$application->offer) {
            return back()->with(
                'error',
                'No offer has been created for this candidate.'
            );
        }

        if ($application->status !== 'selected') {
            return back()->with(
                'error',
                'Only selected candidates can receive an offer.'
            );
        }

        if (
            !$application->interview ||
            $application->interview->status !== 'completed'
        ) {
            return back()->with(
                'error',
                'The interview must be completed before sending an offer.'
            );
        }

        if ($application->offer->status === 'sent') {
            return back()->with(
                'error',
                'This offer has already been sent.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PDF must exist before sending
        |--------------------------------------------------------------------------
        */

        if (!$application->offer->offer_letter_path) {
            return back()->with(
                'error',
                'Please generate the offer letter before sending the offer.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Verify PDF actually exists
        |--------------------------------------------------------------------------
        */

        if (
            !Storage::disk('public')->exists(
                $application->offer->offer_letter_path
            )
        ) {
            return back()->with(
                'error',
                'Offer letter PDF could not be found. Please generate it again.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        */

        try {
            Mail::to(
                $application->user->email
            )->send(
                new OfferSent($application->offer)
            );
        } catch (\Exception $e) {
            logger()->error('Failed sending offer email: ' . $e->getMessage());
        }

        /*
        |--------------------------------------------------------------------------
        | Update status and snapshot
        |--------------------------------------------------------------------------
        */

        $application->offer->update([
            'status' => 'sent',
        ]);

        $application->offer->snapshotVersion($application->offer->version ?? 1, 'sent');

        /*
        |--------------------------------------------------------------------------
        | In-App Notification to Candidate
        |--------------------------------------------------------------------------
        */

        $application->user->notify(
            new ApplicationStatusNotification(
                'Offer Received',
                'You have received an employment offer for ' .
                $application->job->title . '.',
                'offer'
            )
        );

        return back()->with(
            'success',
            'Offer sent successfully to the candidate.'
        );
    }

    /**
     * Generate offer letter PDF.
     */
    public function generateLetter(Application $application)
    {
        $application->load([
            'user',
            'job',
            'offer',
        ]);

        if (!$application->offer) {
            return back()->with(
                'error',
                'No offer exists for this application.'
            );
        }

        $offer = $application->offer;

        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'offers.letter',
            compact('offer')
        );

        /*
        |--------------------------------------------------------------------------
        | File name with versioning
        |--------------------------------------------------------------------------
        */

        $version = $offer->version ?? 1;
        $fileName =
            'offer-letter-' .
            $application->id .
            '-v' .
            $version .
            '-' .
            time() .
            '.pdf';

        $path = 'offers/' . $fileName;

        Storage::disk('public')->put(
            $path,
            $pdf->output()
        );

        $offer->update([
            'offer_letter_path' => $path,
        ]);

        $offer->snapshotVersion($version);

        return back()->with(
            'success',
            'Offer letter generated successfully.'
        );
    }

    /**
     * Revise offer (Joining date change, salary adjustment, version increment).
     */
    public function revise(Request $request, Application $application)
    {
        $application->load(['user', 'job', 'offer.versions']);

        if (!$application->offer) {
            return back()->with('error', 'No offer exists to revise.');
        }

        $validated = $request->validate([
            'joining_date' => [
                'required',
                'date',
            ],
            'salary' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'offer_expiry_date' => [
                'nullable',
                'date',
                'after_or_equal:today',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        $offer = $application->offer;

        // 1. Ensure current version snapshot is saved
        $oldVersion = $offer->version ?? 1;
        $offer->snapshotVersion($oldVersion, 'revised');

        // 2. Increment Version
        $newVersion = $oldVersion + 1;

        // 3. Update master offer state
        $offer->update([
            'version' => $newVersion,
            'joining_date' => $validated['joining_date'],
            'salary' => !empty($validated['salary']) ? $validated['salary'] : $offer->salary,
            'offer_expiry_date' => $validated['offer_expiry_date'] ?? $offer->offer_expiry_date,
            'notes' => $validated['notes'] ?? $offer->notes,
            'status' => 'sent',
            'signed_offer_letter_path' => null,
            'signed_at' => null,
            'joining_date_request_status' => 'approved',
        ]);

        // 4. Generate new version PDF
        $pdf = Pdf::loadView('offers.letter', compact('offer'));
        $fileName = 'offer-letter-' . $application->id . '-v' . $newVersion . '-' . time() . '.pdf';
        $path = 'offers/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());

        $offer->update([
            'offer_letter_path' => $path,
        ]);

        // 5. Snapshot new version in offer_versions
        $offer->snapshotVersion($newVersion, 'sent');

        // 6. Send Email & Notifications to Candidate
        try {
            Mail::to($application->user->email)->send(new OfferRevised($offer));
        } catch (\Exception $e) {
            logger()->error('Failed sending revised offer email: ' . $e->getMessage());
        }

        $application->user->notify(
            new ApplicationStatusNotification(
                'Offer Revised',
                'Your offer for ' . $application->job->title . ' has been revised with a new joining date (Version ' . $newVersion . ').',
                'offer'
            )
        );

        return back()->with(
            'success',
            'Revised offer (Version ' . $newVersion . ') generated and sent to candidate.'
        );
    }

    /**
     * Download offer letter PDF for admin.
     */
    public function downloadLetter(Application $application)
    {
        $application->load(['user', 'offer']);

        if (!$application->offer || !$application->offer->offer_letter_path) {
            return back()->with('error', 'Offer letter PDF is not available.');
        }

        if (!Storage::disk('public')->exists($application->offer->offer_letter_path)) {
            return back()->with('error', 'Offer letter file not found on server.');
        }

        return Storage::disk('public')->download(
            $application->offer->offer_letter_path,
            'Offer_Letter_' . str_replace(' ', '_', $application->user->name) . '_v' . ($application->offer->version ?? 1) . '.pdf'
        );
    }

    /**
     * Download signed offer letter PDF for admin.
     */
    public function downloadSigned(Application $application)
    {
        $application->load(['user', 'offer']);

        if (!$application->offer || !$application->offer->signed_offer_letter_path) {
            return back()->with('error', 'Signed offer letter is not available.');
        }

        if (!Storage::disk('public')->exists($application->offer->signed_offer_letter_path)) {
            return back()->with('error', 'Signed offer file not found on server.');
        }

        return Storage::disk('public')->download(
            $application->offer->signed_offer_letter_path,
            'Signed_Offer_' . str_replace(' ', '_', $application->user->name) . '_v' . ($application->offer->version ?? 1) . '.pdf'
        );
    }
}