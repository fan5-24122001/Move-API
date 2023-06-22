<?php

namespace App\Mail;

use App\Models\TypeReport;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportNotification extends Mailable
{
    use Queueable, SerializesModels;

    protected User $reportedUser;
    protected User $reporter;
    protected TypeReport $typeReport;
    /**
     * Create a new message instance.
     */
    public function __construct(User $reportedUser, User $reporter, TypeReport $typeReport)
    {
        $this->reportedUser = $reportedUser;
        $this->reporter = $reporter;
        $this->typeReport = $typeReport;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Report Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.report_notification',
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
        return $this->subject('Thông báo báo cáo người dùng')
                    ->markdown('emails.report_notificationation')
                    ->with([
                        'reportedUser' => $this->reportedUser,
                        'reporter' => $this->reporter,
                        'typeReport' => $this->typeReport,
                    ]);
    }
}
