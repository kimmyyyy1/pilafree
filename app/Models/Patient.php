<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone_number',
        'is_priority',
        'priority_type',
        'gender',
        'age',
        'notes',
    ];

    protected $casts = [
        'is_priority' => 'boolean',
        'age' => 'integer',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
