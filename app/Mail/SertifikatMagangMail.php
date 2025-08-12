<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SertifikatMagangMail extends Mailable
{
    use Queueable, SerializesModels;

    public $namaMahasiswa;
    public $pdfPath;

    public function __construct($namaMahasiswa, $pdfPath)
    {
        $this->namaMahasiswa = $namaMahasiswa;
        $this->pdfPath = $pdfPath;
    }

    public function build()
    {
        return $this->subject('Sertifikat Magang - PT Perkebunan Nusantara IV')
                    ->markdown('menuadmin.email.sertifikat')
                    ->attach($this->pdfPath, [
                        'as' => 'Sertifikat Magang - ' . $this->namaMahasiswa . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
