<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Favorite extends Model
{
    protected $fillable = [
        'user_id', 
        'product_id'
    ];

    
}
