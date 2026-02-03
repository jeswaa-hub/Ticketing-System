<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::updating(function (Ticket $ticket) {
            if (!$ticket->isDirty('status')) {
                return;
            }

            if (!in_array($ticket->status, ['resolved', 'closed'], true)) {
                return;
            }

            if ($ticket->time_end !== null && $ticket->time_end !== '') {
                return;
            }

            $ticket->time_end = now()->format('H:i');
        });
    }

    protected $fillable = [
        'code',
        'subject',
        'description',
        'requester_name',
        'requester_email',
        'department',
        'assigned_to_user_id',
        'category',
        'ticket_type',
        'priority',
        'status',
        'ticket_date',
        'time_start',
        'time_end',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
        'ticket_date' => 'date',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }
}
