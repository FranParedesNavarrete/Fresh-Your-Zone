<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'email',
        'password',
        'address',
        'role',
        'phone_number',
        'avatar'
    ];

    // Función para permitir el slug
    public static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->slug = Str::slug($user->name, '-'); 
        });
    
        static::updating(function ($user) {
            if ($user->isDirty('name')) {
                $user->slug = Str::slug($user->name, '-');
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación para saber los productos que tiene en venta un usuario
    public function porductsForSale()
    {
        return $this->HasMany(Order::class, 'seller_id');
    }

    // Relación para saber las compras de un usuario
    public function buys()
    {
        return $this->HasMany(Order::class, 'buyer_id');
    }

    // Relación para saber los productos favoritos
    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    // Función para saber si el usuario ha comprado un producto y si ha dejado una reseña, si no ha dejado una reseña, devuelve true
    public function canReview(Product $product): bool
    {
        $hasPurchased = Order::where('buyer_id', $this->id)
            ->where('product_id', $product->id)
            ->whereIn('status', ['pedido', 'entregado'])
            ->exists();
    
        $hasReviewed = $this->reviewedProducts()
            ->where('product_id', $product->id)
            ->exists();
    
        return $hasPurchased && !$hasReviewed;
    }

    // Relación para saber que productos ha dejado el usuario una reseña
    public function reviewedProducts()
    {
        return $this->belongsToMany(Product::class, 'product_reviews')->withPivot('review', 'date');
    }

    // Relación con notificaciones
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
