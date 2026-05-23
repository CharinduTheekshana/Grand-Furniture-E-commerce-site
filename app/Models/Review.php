<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model {
    use HasFactory;
    protected $fillable = ['product_id','user_id','nickname','summary','review','quality','price','value'];
    public function user() { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function getAvgRatingAttribute(): float {
        return round(($this->quality + $this->price + $this->value) / 3, 1);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getAvgStarsAttribute(): int
    {
        $avg = $this->reviews()->avg(
            \DB::raw('(quality + price + value) / 3')
        );
        return $avg ? (int) round($avg) : 0;
    }
}