<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . time();
            }
        });
    }

    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'sale_price', 'old_price', 'discount', 'image',
        'stock', 'is_featured', 'is_active', 'brand',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'price'       => 'decimal:2',
        'old_price'   => 'decimal:2',
        'sale_price'  => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('assets/images/product/1.jpg');
        }

        // Full URL already
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        $image = ltrim($this->image, '/');

        // Filament saves as "products/filename.jpg" → storage/app/public/products/filename.jpg
        if (Storage::disk('public')->exists($image)) {
            return asset('storage/' . $image);
        }

        // Custom admin saves just "filename.jpg" → check storage/products/
        if (Storage::disk('public')->exists('products/' . $image)) {
            return asset('storage/products/' . $image);
        }

        // Check public/uploads/products/ (old path)
        if (file_exists(public_path('uploads/products/' . basename($image)))) {
            return asset('uploads/products/' . basename($image));
        }

        // Check public/assets/images/product/
        if (file_exists(public_path('assets/images/product/' . basename($image)))) {
            return asset('assets/images/product/' . basename($image));
        }

        return asset('assets/images/product/1.jpg');
    }
}