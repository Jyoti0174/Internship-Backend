<!DOCTYPE html>
<html>
<head>
    <title>Create Ticket</title>
</head>
<body>
    <h1>Create New Ticket</h1>
    <a href="{{ route('tickets.index') }}">Back to List</a>

    @if($errors->any())
        <div style="color:red">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('tickets.store') }}" method="POST">
        @csrf
        <p>
            <label>Title:</label><br>
            <input type="text" name="title" value="{{ old('title') }}">
        </p>
        <p>
            <label>Description:</label><br>
            <textarea name="description">{{ old('description') }}</textarea>
        </p>
        <p>
            <label>Status:</label><br>
            <select name="status">
                <option value="open">Open</option>
                <option value="in_progress">In Progress</option>
                <option value="closed">Closed</option>
            </select>
        </p>
        <p>
            <label>Priority:</label><br>
            <select name="priority">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
        </p>
        <p>
            <label>User ID:</label><br>
            <input type="number" name="user_id">
        </p>
        <button type="submit">Create Ticket</button>
    </form>
</body>
</html>