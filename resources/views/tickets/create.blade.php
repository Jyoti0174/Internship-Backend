<!DOCTYPE html>
<html>
<head><title>Create Ticket</title></head>
<body>
<h1>Create New Ticket</h1>
<a href="{{ route('tickets.index') }}">← Back</a><br><br>
@if($errors->any())
    <ul style="color:red">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
<form method="POST" action="{{ route('tickets.store') }}">
    @csrf
    Title: <input type="text" name="title" value="{{ old('title') }}"><br><br>
    Description:<br>
    <textarea name="description" rows="4" cols="40">{{ old('description') }}</textarea><br><br>
    Status:
    <select name="status">
        <option value="open">Open</option>
        <option value="in_progress">In Progress</option>
        <option value="closed">Closed</option>
    </select><br><br>
    Priority:
    <select name="priority">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
    </select><br><br>
    User:
    <select name="user_id">
        @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endforeach
    </select><br><br>
    <button type="submit">Create Ticket</button>
</form>
</body>
</html>