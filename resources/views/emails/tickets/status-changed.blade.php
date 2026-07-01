@component('mail::message')
# Ticket Status Updated

The status of your support ticket has been updated.

@component('mail::panel')
**Ticket #:** {{ $ticket->ticket_number }}
**Title:** {{ $ticket->title }}
**Previous Status:** {{ ucfirst($oldStatus) }}
**New Status:** {{ ucfirst($newStatus) }}
@endcomponent

@component('mail::button', ['url' => config('app.url')])
View Ticket
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent