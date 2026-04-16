<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'image',
        'platform_type',
    ];

    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    
    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }

    
    public function vendors()
    {
        return $this->hasManyThrough(Vendor::class, ProductOffer::class);
    }

    
    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, ProductOffer::class);
    }
}
