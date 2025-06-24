<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SertifikatMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $batch;
    public $pdfContent;

    public function __construct($user, $batch, $pdfContent)
    {
        $this->user = $user;
        $this->batch = $batch;
        $this->pdfContent = $pdfContent;
    }

    public function build()
    {
        return $this->subject("Sertifikat Batch {$this->batch}")
            ->view('emails.sertifikat')
            ->with([
                'name' => $this->user->name,
                'batch' => $this->batch,
            ])
            ->attachData($this->pdfContent, "Sertifikat_{$this->user->name}_Batch{$this->batch}.pdf", [
                'mime' => 'application/pdf',
            ]);
    }
}
