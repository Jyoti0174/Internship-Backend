<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'assigned_to',
        'department_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $lastNumber = self::max('ticket_number') ?? 0;
            $ticket->ticket_number = $lastNumber + 1;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function comments()
{
    return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
}
}