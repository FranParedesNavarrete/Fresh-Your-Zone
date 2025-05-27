<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'date',
        'subject',
        'type',
        'updated_at'
    ];

    // Relación con usuarios
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
