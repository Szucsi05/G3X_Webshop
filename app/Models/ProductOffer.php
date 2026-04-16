<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOffer extends Model
{
    protected $fillable = [
        'product_id',
        'vendor_id',
        'platform_id',
        'price',
        'stock',
        'region',
        'delivery_type',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    
    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    
    public static function scopeLowestPrice($query, $productId)
    {
        return $query->where('product_id', $productId)
            ->where('status', 'active')
            ->orderBy('price', 'asc');
    }

    
    public static function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->where('stock', '>', 0);
    }
}
