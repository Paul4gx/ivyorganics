<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Order;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'tagline',
        'description',
        'features',
        'image',
        'texture',
        'color',
        'scent',
        'size',
        'category',
        'ingredients',
        'perfect_for',
        'is_featured',
        'is_active'
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean'
    ];

    public static function allFromJson()
    {
        $json = File::get(public_path('products.json'));
        return collect(json_decode($json, true)['products']);
    }

    public static function findFromJson($id)
    {
        return self::allFromJson()->firstWhere('id', $id);
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/' . $this->image);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the count of times this product appears in order items
     */
    public function getOrderItemsCountAttribute()
    {
        $count = 0;
        $orders = Order::whereNotNull('order_items')->get();
        
        foreach ($orders as $order) {
            if ($order->order_items) {
                foreach ($order->order_items as $item) {
                    if (isset($item['product_id']) && $item['product_id'] == $this->id) {
                        $count += $item['quantity'] ?? 1;
                    }
                }
            }
        }
        
        return $count;
    }
}