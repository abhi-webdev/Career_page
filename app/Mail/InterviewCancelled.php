<?php

namespace App\Mail;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewCancelled extends Mailable
{
    use Queueable, SerializesModels;

    public Interview $interview;

    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }

    public function build()
    {
        return $this
            ->subject(
                'Interview Cancelled - ' .
                $this->interview->application->job->title
            )
            ->view('emails.interview-cancelled');
    }
}