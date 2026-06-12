<!DOCTYPE html>
<html>
<head><title>Ticket Details</title></head>
<body>
<h1>Ticket #{{ $ticket->id }}</h1>
<a href="{{ route('tickets.index') }}">← Back</a><br><br>
<p><strong>Title:</strong> {{ $ticket->title }}</p>
<p><strong>Description:</strong> {{ $ticket->description }}</p>
<p><strong>Status:</strong> {{ $ticket->status }}</p>
<p><strong>Priority:</strong> {{ $ticket->priority }}</p>
<p><strong>Created by:</strong> {{ $ticket->user->name ?? 'N/A' }}</p>
<p><strong>Created at:</strong> {{ $ticket->created_at }}</p>
<a href="{{ route('tickets.edit', $ticket->id) }}">Edit</a>
</body>
</html>