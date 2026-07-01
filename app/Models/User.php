<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'role',
        'email_notifications',
        'notify_ticket_created',
        'notify_ticket_assigned',
        'notify_status_changed',
        'notify_comment_added',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
{
    return [
        'email_verified_at'      => 'datetime',
        'password'               => 'hashed',
        'notify_ticket_created'  => 'boolean',
        'notify_ticket_assigned' => 'boolean',
        'notify_status_changed'  => 'boolean',
        'notify_comment_added'   => 'boolean',
    ];
}

    // User belongs to Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // User has many Tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}