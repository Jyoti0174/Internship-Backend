<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'action',
        'description',
        'field_name',
        'old_value',
        'new_value',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(int $ticketId, ?int $userId, string $action, string $description, ?string $field = null, $old = null, $new = null): self
    {
        return self::create([
            'ticket_id'   => $ticketId,
            'user_id'     => $userId,
            'action'      => $action,
            'description' => $description,
            'field_name'  => $field,
            'old_value'   => $old !== null ? (string) $old : null,
            'new_value'   => $new !== null ? (string) $new : null,
        ]);
    }
}