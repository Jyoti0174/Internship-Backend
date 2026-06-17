<!DOCTYPE html>
<html>
<head><title>Tickets</title></head>
<body>
<h1>All Tickets</h1>
@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif
<a href="{{ route('tickets.create') }}">Create New Ticket</a>
<br><br>
<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Assigned To</th>
        <th>Actions</th>
    </tr>
    @foreach($tickets as $ticket)
    <tr>
        <td>{{ $ticket->id }}</td>
        <td>{{ $ticket->title }}</td>
        <td>{{ $ticket->status }}</td>
        <td>{{ $ticket->priority }}</td>
        <td>{{ $ticket->assignedTo?->name ?? 'Unassigned' }}</td>
        <td>
            <a href="{{ route('tickets.show', $ticket->id) }}">View</a>
            <a href="{{ route('tickets.edit', $ticket->id) }}">Edit</a>
            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
</body>
</html>