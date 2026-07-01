@component('mail::message')
# New Ticket Created

A new support ticket has been submitted.

@component('mail::panel')
**Ticket #:** {{ $ticket->ticket_number }}
**Title:** {{ $ticket->title }}
**Priority:** {{ ucfirst($ticket->priority) }}
**Status:** {{ ucfirst($ticket->status) }}
**Created By:** {{ $ticket->user->name ?? 'N/A' }}
@endcomponent

@component('mail::button', ['url' => config('app.url')])
View Ticket
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent