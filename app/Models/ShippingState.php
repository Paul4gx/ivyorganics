<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingState extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'shipping_fee',
        'is_active',
        'shipping_country_id'
    ];

    protected $casts = [
        'shipping_fee' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function country()
    {
        return $this->belongsTo(ShippingCountry::class, 'shipping_country_id');
    }
} 