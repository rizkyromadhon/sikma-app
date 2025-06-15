<?php

namespace App\Mail;

use App\Models\Laporan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $laporan;

    public function __construct(Laporan $laporan)
    {
        $this->laporan = $laporan;
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Balasan untuk Laporan Lupa Password Anda',
        );
    }

    public function content()
    {
        return new Content(
            markdown: 'emails.admin-reply',
        );
    }
}
