<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'color_id', 'image', 'sort_order'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('assets/images/product/1.jpg');
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        $image = ltrim($this->image, '/');

        if (Storage::disk('public')->exists($image)) {
            return asset('storage/' . $image);
        }

        if (file_exists(public_path('uploads/products/' . basename($image)))) {
            return asset('uploads/products/' . basename($image));
        }

        return asset('assets/images/product/1.jpg');
    }
}