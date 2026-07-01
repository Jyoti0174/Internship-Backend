@component('mail::message')
# New Comment Added

A new comment has been added to your support ticket.

@component('mail::panel')
**Ticket #:** {{ $ticket->ticket_number }}
**Title:** {{ $ticket->title }}
**Comment By:** {{ $comment->user->name ?? 'N/A' }}
**Comment:** {{ $comment->body }}
@endcomponent

@component('mail::button', ['url' => config('app.url')])
View Ticket
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent