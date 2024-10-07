<?php

namespace App\Mail;

use App\Models\PengajuanModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanDiterimaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;
    public $bulanRomawi;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PengajuanModel $pengajuan,$bulanRomawi)
    {
        $this->pengajuan = $pengajuan;
        $this->bulanRomawi = $bulanRomawi;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */

    public function build(){
        $filePath = storage_path('app/public/' . $this->pengajuan->balasanTerima);

        return $this->subject('Pengajuan Magang Diterima')
                    ->view('menuadmin.email.pengajuan_diterima')
                    ->attach($filePath,[
                        'as' => 'Surat_Balasan_Terima.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Pengajuan Diterima Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    // public function content()
    // {
    //     // return new Content(
    //     //     view: 'view.name',
    //     // );
    // }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
