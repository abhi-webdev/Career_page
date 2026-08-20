<?php

namespace App\Mail;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterviewRescheduled extends Mailable
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
                'Interview Rescheduled - ' .
                $this->interview->application->job->title
            )
            ->view('emails.interview-rescheduled');
    }
}