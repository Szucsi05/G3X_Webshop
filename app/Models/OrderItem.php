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

    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    
    public function productOffer(): BelongsTo
    {
        return $this->belongsTo(ProductOffer::class);
    }

    
    public function product()
    {
        return $this->productOffer->product();
    }

    
    public function vendor()
    {
        return $this->productOffer->vendor();
    }
}
