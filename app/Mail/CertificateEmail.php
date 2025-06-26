<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $batch;
    public $levelTitle;
    public $pdfPath;

    public function __construct($name, $batch, $levelTitle, $pdfPath)
    {
        $this->name = $name;
        $this->batch = $batch;
        $this->levelTitle = $levelTitle;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject("Sertifikat Anda - Level {$this->levelTitle}")
            ->view('emails.certificate')
            ->attach($this->pdfPath, [
                'as' => "Sertifikat_{$this->name}_Batch{$this->batch}.pdf",
                'mime' => 'application/pdf',
            ]);
    }
}
