<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'id',
        'name',
        'slug',
        'price',
        'description',
        'category',
        'state',
        'stock',
        'images'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name, '-') . '-' . uniqid(); // agrega uniqid para evitar duplicados
        });
    
        static::updating(function ($product) {
            if ($product->isDirty('name')) {
                $product->slug = Str::slug($product->name, '-') . '-' . uniqid();
            }
        });
    }

    public function sellers()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    public function reviews()
    {
        return $this->belongsToMany(User::class, 'product_reviews')->withPivot('review', 'date');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }

    public function deliveryPoints()
    {
        return $this->belongsToMany(DeliveryPoint::class, 'delivery_product');
    }
}
