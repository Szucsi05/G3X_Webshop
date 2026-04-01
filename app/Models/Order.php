<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'total_amount',
        'payment_method',
        'status',
        'currency',
        'billing_name',
        'billing_email',
        'billing_phone',
        'billing_country',
        'billing_city',
        'billing_postal',
        'billing_street',
        'billing_company_name',
        'billing_tax_id',
        'account_type',
    ];

    /**
     * Rendelés felhasználója
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Rendelés tételei (order_items)
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Összes termék ajánlat a rendelésben (hasManyThrough-on keresztül)
     */
    public function productOffers()
    {
        return $this->hasManyThrough(ProductOffer::class, OrderItem::class);
    }
}
