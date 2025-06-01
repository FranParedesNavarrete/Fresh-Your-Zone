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

    // Función para permitir el slug
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

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relación con el vendedor
    public function sellers()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Relación para saber de quien es favorito el producto
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites');
    }

    // Relación para obtener las reseñas al producto
    public function reviews()
    {
        return $this->belongsToMany(User::class, 'product_reviews')->withPivot('review', 'date');
    }

    // Relacion con pedidos
    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }
}
