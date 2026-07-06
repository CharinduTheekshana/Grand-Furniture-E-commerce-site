<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Color extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color_code'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_color')
                    ->withTimestamps();
    }
}