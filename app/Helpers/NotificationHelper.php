<?php

namespace App\Helpers;

use App\Mail\TicketNotificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotificationHelper
{
    public static function sendIfEnabled(User $user, string $eventType, array $data): void
    {
        // Master switch check
        if (!$user->email_notifications) {
            return;
        }

        // Granular preference check
        $prefs = $user->notification_preferences ?? [];
        $enabled = $prefs[$eventType] ?? false;

        if ($enabled) {
            Mail::to($user->email)->send(new TicketNotificationMail($eventType, $data));
        }
    }
}