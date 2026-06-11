<!DOCTYPE html>
<html>
<head>
    <title>Ticket Details</title>
</head>
<body>
    <h1>Ticket Details</h1>
    <a href="{{ route('tickets.index') }}">Back to List</a>

    <table border="1">
        <tr>
            <th>ID</th>
            <td>{{ $ticket->id }}</td>
        </tr>
        <tr>
            <th>Title</th>
            <td>{{ $ticket->title }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $ticket->description }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $ticket->status }}</td>
        </tr>
        <tr>
            <th>Priority</th>
            <td>{{ $ticket->priority }}</td>
        </tr>
        <tr>
            <th>Assigned User</th>
            <td>{{ $ticket->user->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $ticket->created_at }}</td>
        </tr>
    </table>

    <a href="{{ route('tickets.edit', $ticket->id) }}">Edit Ticket</a>

    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Ticket</button>
    </form>
</body>
</html>