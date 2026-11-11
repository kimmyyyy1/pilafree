<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'room_number',
        'description',
        'daily_quota',
        'avg_consultation_minutes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'daily_quota' => 'integer',
        'avg_consultation_minutes' => 'integer',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function waitingTickets(): HasMany
    {
        return $this->hasMany(Ticket::class)
            ->whereDate('created_at', today())
            ->where('status', 'waiting')
            ->orderByDesc('is_priority')
            ->orderBy('queue_sequence');
    }

    public function currentServingTicket()
    {
        return $this->hasOne(Ticket::class)
            ->whereDate('created_at', today())
            ->whereIn('status', ['called', 'serving'])
            ->latest('called_at');
    }
}
