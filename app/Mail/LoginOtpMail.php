<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoginOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode Verifikasi Login: ' . $this->otp . ' – PT Nusantara Digital Express',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.login-otp',
        );
    }

    public function attachments(): array
    {
        $logoPath = public_path('images/logo.png');
        if (file_exists($logoPath)) {
            return [
                \Illuminate\Mail\Mailables\Attachment::fromPath($logoPath)
                    ->as('logo.png')
                    ->withMime('image/png'),
            ];
        }
        return [];
    }
}
