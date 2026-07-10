<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 11px; color: #333; padding: 20px; }

        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2d3748; padding-bottom: 12px; }
        .header h1 { font-size: 20px; color: #2d3748; margin-bottom: 4px; }
        .header p { font-size: 11px; color: #666; }

        .meta { display: flex; justify-content: space-between; margin-bottom: 16px; }
        .meta-box { background: #f7fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 8px 14px; flex: 1; margin-right: 10px; }
        .meta-box:last-child { margin-right: 0; }
        .meta-box label { font-size: 10px; color: #888; display: block; margin-bottom: 2px; }
        .meta-box span { font-size: 12px; font-weight: bold; color: #2d3748; }

        .filters { margin-bottom: 14px; background: #fffbeb; border: 1px solid #f6e05e; border-radius: 4px; padding: 8px 12px; }
        .filters h4 { font-size: 11px; color: #744210; margin-bottom: 4px; }
        .filters span { font-size: 10px; color: #555; margin-right: 12px; }

        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead tr { background: #2d3748; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background: #f7fafc; }
        tbody tr:hover { background: #edf2f7; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; font-size: 10px; }

        .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-open { background: #c6f6d5; color: #276749; }
        .badge-in_progress { background: #bee3f8; color: #2a4365; }
        .badge-closed { background: #fed7d7; color: #742a2a; }
        .badge-high { background: #fed7d7; color: #742a2a; }
        .badge-medium { background: #fefcbf; color: #744210; }
        .badge-low { background: #c6f6d5; color: #276749; }

        .footer { margin-top: 20px; text-align: center; font-size: 9px; color: #aaa; border-top: 1px solid #e2e8f0; padding-top: 8px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Generated on: {{ $generated_at }}</p>
    </div>

    {{-- Meta Info --}}
    <div class="meta">
        <div class="meta-box">
            <label>Total Records</label>
            <span>{{ $total }}</span>
        </div>
        <div class="meta-box">
            <label>Report Date</label>
            <span>{{ $generated_at }}</span>
        </div>
        <div class="meta-box">
            <label>System</label>
            <span>Employee Helpdesk</span>
        </div>
    </div>

    {{-- Applied Filters --}}
    @if(count($filters) > 0)
    <div class="filters">
        <h4>Applied Filters:</h4>
        @foreach($filters as $key => $value)
            <span><strong>{{ $key }}:</strong> {{ $value }}</span>
        @endforeach
    </div>
    @endif

    {{-- Tickets Table --}}
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Department</th>
                <th>Created By</th>
                <th>Assigned To</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $index => $ticket)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $ticket->title }}</td>
                <td>
                    <span class="badge badge-{{ $ticket->status }}">
                        {{ ucfirst(str_replace('_', ' ', $ticket->status ?? 'N/A')) }}
                    </span>
                </td>
                <td>
                    <span class="badge badge-{{ $ticket->priority }}">
                        {{ ucfirst($ticket->priority ?? 'N/A') }}
                    </span>
                </td>
                <td>{{ $ticket->department->name ?? 'N/A' }}</td>
                <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                <td>{{ $ticket->assignedTo->name ?? 'Unassigned' }}</td>
                <td>{{ $ticket->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding: 20px; color: #888;">
                    No tickets found for the selected filters.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    <div class="footer">
        Employee Helpdesk System &mdash; Confidential Report &mdash; {{ $generated_at }}
    </div>

</body>
</html>