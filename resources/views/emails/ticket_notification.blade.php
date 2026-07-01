<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .container { background: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        .header { background: #4f46e5; color: white; padding: 15px 20px; border-radius: 6px 6px 0 0; }
        .content { padding: 20px 0; }
        .footer { margin-top: 20px; font-size: 12px; color: #999; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2 style="margin:0">{{ config('app.name') }} Notification</h2>
    </div>
    <div class="content">

        @if($eventType === 'ticket_created')
            <p>A new ticket has been created.</p>
        @elseif($eventType === 'ticket_assigned')
            <p>A ticket has been assigned to you.</p>
        @elseif($eventType === 'status_changed')
            <p>A ticket status has been updated.</p>
        @elseif($eventType === 'comment_added')
            <p>A new comment has been added to your ticket.</p>
        @endif

        <table style="width:100%; border-collapse: collapse; margin-top: 15px;">
            @foreach($data as $key => $value)
                @if($value)
                <tr>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; color: #666; width: 40%;">
                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                    </td>
                    <td style="padding: 8px; border-bottom: 1px solid #eee; font-weight: bold;">
                        {{ $value }}
                    </td>
                </tr>
                @endif
            @endforeach
        </table>
    </div>
    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}.</p>
    </div>
</div>
</body>
</html>