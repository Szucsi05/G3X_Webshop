<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    
    use HasFactory, Notifiable, HasApiTokens;

    
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'cart_data',
        'card_number',
        'card_expiry',
        'card_cvv',
    ];

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'cart_data' => 'array',
        ];
    }

    
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
