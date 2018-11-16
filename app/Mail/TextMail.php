<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TextMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $msg = '';

    /**
     * Create a new message instance.
     *
     * @param string $msg
     */
    public function __construct($msg = '')
    {
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.text', ['msg' => $this->msg]);
    }
}
