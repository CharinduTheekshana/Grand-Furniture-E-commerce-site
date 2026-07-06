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
        'offer_badge', 'offer_type',
    'offer_start_date', 'offer_end_date', 'offer_status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'price'       => 'decimal:2',
        'old_price'   => 'decimal:2',
        'sale_price'  => 'decimal:2',
        'offer_start_date' => 'datetime',
        'offer_end_date'   => 'datetime',
        'offer_status'     => 'boolean',
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

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color')
                    ->withTimestamps();
    }

    // ── Helper: is offer currently active? (Offers)
public function getIsOfferActiveAttribute(): bool
{
    if (!$this->offer_status) return false;
    if (empty($this->offer_badge)) return false;

    $now = now();

    if ($this->offer_start_date && $now->lt($this->offer_start_date)) return false;
    if ($this->offer_end_date   && $now->gt($this->offer_end_date))   return false;

    return true;
}
// ── Helper: badge CSS color class ───────────────────
public function getOfferBadgeClassAttribute(): string
{
    return match($this->offer_type) {
        'flash_sale'    => 'offer-badge-flash',
        'free_delivery' => 'offer-badge-free-delivery',
        'bogo'          => 'offer-badge-bogo',
        'clearance'     => 'offer-badge-clearance',
        'flash'         => 'offer-badge-flash',
        'percentage'    => 'offer-badge-percentage',
        'fixed'         => 'offer-badge-fixed',
        'weekend'       => 'offer-badge-weekend',
        'mega'          => 'offer-badge-mega',
        default         => 'offer-badge-default',
    };
}
}