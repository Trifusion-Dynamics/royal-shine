<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Enquiry $enquiry,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Jewellery Enquiry - Royal Shine',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.enquiry-received',
        );
    }
}
