<?php

namespace Cupa\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InactiveReport extends Mailable
{
    use Queueable, SerializesModels;

    public $logFile;

    /**
     * Create a new message instance.
     */
    public function __construct($logFile = null)
    {
        $this->logFile = null;
        if ($logFile && file_exists($logFile)) {
            $this->logFile = $logFile;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from('webmaster@cincyultimate.org')
            ->subject('[CUPA] Inative Report '.date('Y-m-d'));

        // attach the log file if exists
        if (isset($this->logFile)) {
            $email->attach($this->logFile);
        }

        return $email->view('emails.inactive_report');
    }
}
