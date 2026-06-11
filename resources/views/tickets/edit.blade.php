<!DOCTYPE html>
<html>
<head>
    <title>Edit Ticket</title>
</head>
<body>
    <h1>Edit Ticket</h1>
    <a href="{{ route('tickets.index') }}">Back to List</a>

    @if($errors->any())
        <div style="color:red">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
        @csrf
        @method('PUT')
        <p>
            <label>Title:</label><br>
            <input type="text" name="title" value="{{ old('title', $ticket->title) }}">
        </p>
        <p>
            <label>Description:</label><br>
            <textarea name="description">{{ old('description', $ticket->description) }}</textarea>
        </p>
        <p>
            <label>Status:</label><br>
            <select name="status">
                <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
            </select>
        </p>
        <p>
            <label>Priority:</label><br>
            <select name="priority">
                <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
                <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
            </select>
        </p>
        <p>
            <label>User ID:</label><br>
            <input type="number" name="user_id" value="{{ old('user_id', $ticket->user_id) }}">
        </p>
        <button type="submit">Update Ticket</button>
    </form>
</body>
</html>