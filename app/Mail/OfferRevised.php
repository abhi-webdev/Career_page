<?php

namespace App\Mail;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OfferRevised extends Mailable
{
    use Queueable, SerializesModels;

    public Offer $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function build()
    {
        $mail = $this
            ->subject(
                'Revised Employment Offer (Version ' . ($this->offer->version ?? 2) . ') - ' .
                $this->offer->application->job->title
            )
            ->view('emails.offer-revised');

        if ($this->offer->offer_letter_path && Storage::disk('public')->exists($this->offer->offer_letter_path)) {
            $mail->attach(
                Storage::disk('public')->path($this->offer->offer_letter_path),
                [
                    'as' => 'Revised-Offer-Letter-v' . ($this->offer->version ?? 2) . '.pdf',
                    'mime' => 'application/pdf',
                ]
            );
        }

        return $mail;
    }
}
