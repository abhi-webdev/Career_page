<?php

namespace App\Mail;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class OfferSent extends Mailable
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
                'Employment Offer - ' .
                $this->offer->application->job->title
            )
            ->view('emails.offer-sent');


        if ($this->offer->offer_letter_path) {

            $mail->attach(
                Storage::disk('public')
                    ->path($this->offer->offer_letter_path),
                [
                    'as' => 'Offer-Letter.pdf',
                    'mime' => 'application/pdf',
                ]
            );

        }


        return $mail;
    }
}