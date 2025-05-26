<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'seller_id',
        'buyer_id',
        'product_id',
        'status',
        'date',
        'price',
        'delivery_address',
        'delivery_phone',
        'created_at',
        'updated_at'
    ];

    // Relación con el vendedor (user que es el vendedor)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Relación con el comprador (user que es el comprador)
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Relación con el producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
