@component('mail::message')
# Ticket Assigned to You

A support ticket has been assigned to you.

@component('mail::panel')
**Ticket #:** {{ $ticket->ticket_number }}
**Title:** {{ $ticket->title }}
**Priority:** {{ ucfirst($ticket->priority) }}
**Status:** {{ ucfirst($ticket->status) }}
**Assigned To:** {{ $ticket->assignedTo->name ?? 'N/A' }}
@endcomponent

@component('mail::button', ['url' => config('app.url')])
View Ticket
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent