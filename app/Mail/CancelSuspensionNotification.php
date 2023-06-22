<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CancelSuspensionNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected User $reportedUser;
    /**
     * Create a new message instance.
     */
    public function __construct(User $reportedUser)
    {
        $this->reportedUser = $reportedUser;
    }

    public function build()
    {
        return $this->subject('Thông báo hết hạn đình chỉ hay đình chỉ được hủy bỏ')
                    ->markdown('emails.cancel_suspension')
                    ->with([
                        'reportedUser' => $this->reportedUser,
                    ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cancel Suspension Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cancel_suspension',
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
