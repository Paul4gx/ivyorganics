<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'default_shipping_fee',
        'is_active',
        'is_international'
    ];

    protected $casts = [
        'default_shipping_fee' => 'decimal:2',
        'is_active' => 'boolean',
        'is_international' => 'boolean'
    ];

    public function states()
    {
        return $this->hasMany(ShippingState::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInternational($query)
    {
        return $query->where('is_international', true);
    }

    public function scopeDomestic($query)
    {
        return $query->where('is_international', false);
    }

    public function getShippingFee($stateId = null)
    {
        if ($stateId) {
            $state = $this->states()->find($stateId);
            if ($state && $state->is_active) {
                return $state->shipping_fee;
            }
        }
        
        return $this->default_shipping_fee;
    }
} 