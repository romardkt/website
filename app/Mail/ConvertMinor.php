<?php

namespace Cupa\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ConvertMinor extends Mailable
{
    use Queueable, SerializesModels;

    public $parent;
    public $minors;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->parent = $data['parent'];
        $this->minors = $data['minors'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from('webmaster@cincyultimate.org')
            ->subject('[CUPA] Minor account is 18 or older');

        return $email->view('emails.minor_convert');
    }
}
