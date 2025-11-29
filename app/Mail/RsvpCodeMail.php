<?php

namespace App\Mail;

use App\Models\Rsvp;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RsvpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $rsvp;

    public function __construct(Rsvp $rsvp)
    {
        $this->rsvp = $rsvp;
    }

    public function build()
    {
        return $this->subject('Michael & Amna RSVP Details')
            ->view('email.rsvp_code');
    }
}

