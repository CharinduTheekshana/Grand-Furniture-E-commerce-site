<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductOffer extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'badge_type',
        'start_date',
        'end_date',
        'is_active'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}