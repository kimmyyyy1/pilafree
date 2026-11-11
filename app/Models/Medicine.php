<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'generic_name',
        'brand_name',
        'dosage',
        'category',
        'unit',
        'stock_quantity',
        'low_stock_threshold',
        'instructions',
        'is_available',
    ];

    protected $casts = [
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'is_available' => 'boolean',
    ];

    public function dispenses(): HasMany
    {
        return $this->hasMany(Dispense::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity <= $this->low_stock_threshold;
    }
}
