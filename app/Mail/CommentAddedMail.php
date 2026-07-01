<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ticket $ticket,
        public Comment $comment,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Comment on Ticket: #' . $this->ticket->ticket_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.tickets.comment-added',
        );
    }
}