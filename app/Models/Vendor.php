<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'email',
        'description',
        'rating',
        'website',
        'logo_url',
        'status',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
    ];

    
    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }

    
    public function products()
    {
        return $this->hasManyThrough(Product::class, ProductOffer::class);
    }
}
