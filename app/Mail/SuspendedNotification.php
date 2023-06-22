<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuspendedNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected User $reportedUser;
    protected $suspensionDays;
    /**
     * Create a new message instance.
     */
    public function __construct(User $reportedUser, $suspensionDays)
    {
        $this->reportedUser = $reportedUser;
        $this->suspensionDays = $suspensionDays;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Suspended Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.suspended',
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

    public function build()
    {
        return $this->subject('Thông báo tạm ngưng tài khoản')
                    ->markdown('emails.suspended')
                    ->with([
                        'reportedUser' => $this->reportedUser,
                        'suspensionDays' => $this->suspensionDays,
                    ]);
    }
}
