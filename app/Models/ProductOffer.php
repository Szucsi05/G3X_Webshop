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

    /**
     * Az ajánlathoz tartozó terméket adja vissza
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Az ajánlathoz tartozó eladót adja vissza
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Az ajánlathoz tartozó platformot adja vissza
     */
    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }

    /**
     * Erre az ajánlatra vonatkozó rendelési tételek
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * A legalacsonyabb ár (scope)
     */
    public static function scopeLowestPrice($query, $productId)
    {
        return $query->where('product_id', $productId)
            ->where('status', 'active')
            ->orderBy('price', 'asc');
    }

    /**
     * Elérhető ajánlatok (scope)
     */
    public static function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->where('stock', '>', 0);
    }
}
