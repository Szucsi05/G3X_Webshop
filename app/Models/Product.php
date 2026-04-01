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

    /**
     * Termék kategóriája
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Terméket kínáló eladók (product_offers)
     */
    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }

    /**
     * Direkten az összes eladó
     */
    public function vendors()
    {
        return $this->hasManyThrough(Vendor::class, ProductOffer::class);
    }

    /**
     * Az összes rendelési tétel ehhez a termékhez
     */
    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, ProductOffer::class);
    }
}
