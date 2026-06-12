<!DOCTYPE html>
<html>
<head><title>Edit Ticket</title></head>
<body>
<h1>Edit Ticket #{{ $ticket->id }}</h1>
<a href="{{ route('tickets.index') }}">← Back</a><br><br>
@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
<form method="POST" action="{{ route('tickets.update', $ticket->id) }}">
    @csrf @method('PUT')
    Title: <input type="text" name="title" value="{{ $ticket->title }}"><br><br>
    Description:<br>
    <textarea name="description" rows="4" cols="40">{{ $ticket->description }}</textarea><br><br>
    Status:
    <select name="status">
        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
        <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
    </select><br><br>
    Priority:
    <select name="priority">
        <option value="low" {{ $ticket->priority == 'low' ? 'selected' : '' }}>Low</option>
        <option value="medium" {{ $ticket->priority == 'medium' ? 'selected' : '' }}>Medium</option>
        <option value="high" {{ $ticket->priority == 'high' ? 'selected' : '' }}>High</option>
    </select><br><br>
    User:
    <select name="user_id">
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ $ticket->user_id == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select><br><br>
    <button type="submit">Update Ticket</button>
</form>
</body>
</html>