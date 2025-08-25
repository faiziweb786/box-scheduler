<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $boxCount;
    public $fullName;

    /**
     * Create a new message instance.
     */
    public function __construct(int $boxCount, string $fullName = 'Your Full Name')
    {
        $this->boxCount = $boxCount;
        $this->fullName = $fullName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '1st Task Done with ' . $this->fullName,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.task-completed',
            with: [
                'boxCount' => $this->boxCount,
                'fullName' => $this->fullName,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
