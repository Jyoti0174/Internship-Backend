<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $eventType;
    public array $data;

    public function __construct(string $eventType, array $data)
    {
        $this->eventType = $eventType;
        $this->data = $data;
    }

    public function build(): self
    {
        $subjects = [
            'ticket_created'  => 'New Ticket Created: ' . ($this->data['title'] ?? ''),
            'ticket_assigned' => 'Ticket Assigned to You: ' . ($this->data['title'] ?? ''),
            'status_changed'  => 'Ticket Status Updated: ' . ($this->data['title'] ?? ''),
            'comment_added'   => 'New Comment on Ticket: ' . ($this->data['title'] ?? ''),
        ];

        return $this->subject($subjects[$this->eventType] ?? 'Helpdesk Notification')
                    ->view('emails.ticket_notification');
    }
}