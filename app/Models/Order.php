<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'country',
        'subtotal',
        'shipping_fee',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'paystack_reference',
        'order_items'
    ];

    protected $casts = [
        'order_items' => 'array',
        'subtotal' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->zip}, {$this->country}";
    }

    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Get the items relationship for the order
     * This works with the JSON order_items column
     */
    public function getItemsAttribute()
    {
        if (!$this->order_items) {
            return collect();
        }

        return collect($this->order_items)->map(function ($item) {
            // Create a simple object that mimics a relationship
            return (object) [
                'product' => (object) [
                    'id' => $item['product_id'] ?? null,
                    'name' => $item['name'] ?? '',
                    'price' => $item['price'] ?? 0,
                    'image' => $item['image'] ?? null,
                ],
                'quantity' => $item['quantity'] ?? 0,
                'price' => $item['price'] ?? 0,
                'subtotal' => $item['subtotal'] ?? 0,
            ];
        });
    }
} 