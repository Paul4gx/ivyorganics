<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Product extends Model
{
    protected $guarded = [];

    public static function allFromJson()
    {
        $json = File::get(public_path('products.json'));
        return collect(json_decode($json, true)['products']);
    }

    public static function findFromJson($id)
    {
        return self::allFromJson()->firstWhere('id', $id);
    }
}