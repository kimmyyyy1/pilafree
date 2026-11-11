<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'patient_id',
        'department_id',
        'status',
        'queue_sequence',
        'is_priority',
        'assigned_room',
        'channel',
        'called_at',
        'serving_at',
        'completed_at',
        'remarks',
    ];

    protected $casts = [
        'is_priority' => 'boolean',
        'queue_sequence' => 'integer',
        'called_at' => 'datetime',
        'serving_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function dispenses(): HasMany
    {
        return $this->hasMany(Dispense::class);
    }
}
