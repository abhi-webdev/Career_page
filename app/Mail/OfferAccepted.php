<?php

namespace App\Mail;

use App\Models\Employee;
use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OfferAccepted extends Mailable
{
    use Queueable, SerializesModels;

    public Offer $offer;
    public Employee $employee;

    public function __construct(Offer $offer, Employee $employee)
    {
        $this->offer = $offer;
        $this->employee = $employee;
    }

    public function build()
    {
        return $this
            ->subject(
                'Offer Accepted Successfully - ' .
                $this->offer->application->job->title
            )
            ->view('emails.offer-accepted');
    }
}
