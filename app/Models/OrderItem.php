<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_offer_id',
        'price_at_purchase',
        'quantity',
        'license_key',
        'account_details',
    ];

    protected $casts = [
        'price_at_purchase' => 'decimal:2',
        'account_details' => 'array',
    ];

    /**
     * A rendeléshez tartozó tétel
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Az ajánlathoz tartozó terméket adja vissza
     */
    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    /**
     * Rövidítés: direkten a terméket adja vissza
     */
    public function product()
    {
        return $this->productOffer->product();
    }

    /**
     * Rövidítés: direkten az eladót adja vissza
     */
    public function vendor()
    {
        return $this->productOffer->vendor();
    }
}
