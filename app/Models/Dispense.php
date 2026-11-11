<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispense extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'medicine_id',
        'quantity',
        'dosage_instructions',
        'dispensed_by',
        'dispensed_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'dispensed_at' => 'datetime',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
