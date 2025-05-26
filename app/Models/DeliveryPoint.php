<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryPoint extends Model
{
    protected $fillable = [
        'id',
        'name',
        'address',
        'availability',
        'contact'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'delivery_product');
    }
}
