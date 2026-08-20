<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OfferSent;
use App\Models\Application;
use App\Models\Offer;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

use App\Notifications\ApplicationStatusNotification;


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
            'offer',
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
                'after_or_equal:joining_date',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);

        Offer::updateOrCreate(
            [
                'application_id' => $application->id,
            ],
            [
                'salary' => $validated['salary'],

                'joining_date' => $validated['joining_date'],

                'offer_expiry_date' =>
                    $validated['offer_expiry_date'] ?? null,

                'notes' =>
                    $validated['notes'] ?? null,

                'status' => 'draft',
            ]
        );

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

        Mail::to(
            $application->user->email
        )->send(
            new OfferSent($application->offer)
        );

        /*
        |--------------------------------------------------------------------------
        | Update status only after email succeeds
        |--------------------------------------------------------------------------
        */

        $application->offer->update([
            'status' => 'sent',
        ]);

        return back()->with(
            'success',
            'Offer sent successfully to the candidate.'
        );

        
        $application->user->notify(
    new ApplicationStatusNotification(
        'Offer Received',
        'You have received an employment offer for ' .
        $application->job->title .
        '.',
        'offer'
    )
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
        | File name
        |--------------------------------------------------------------------------
        */

        $fileName =
            'offer-letter-' .
            $application->id .
            '-' .
            time() .
            '.pdf';

        $path = 'offers/' . $fileName;

        /*
        |--------------------------------------------------------------------------
        | Save PDF
        |--------------------------------------------------------------------------
        */

        Storage::disk('public')->put(
            $path,
            $pdf->output()
        );

        /*
        |--------------------------------------------------------------------------
        | Save path in database
        |--------------------------------------------------------------------------
        */

        $offer->update([
            'offer_letter_path' => $path,
        ]);

        return back()->with(
            'success',
            'Offer letter generated successfully.'
        );
    }
}