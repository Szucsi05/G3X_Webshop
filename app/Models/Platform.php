<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Platform extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    
    public function offers(): HasMany
    {
        return $this->hasMany(ProductOffer::class);
    }
}
