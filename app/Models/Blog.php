<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'image', 'is_published', 'excerpt'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Reading time in minutes
    public function getReadingTimeAttribute(): int
    {
        $wordCount = str_word_count(strip_tags($this->content ?? ''));
        return max(1, (int) ceil($wordCount / 200));
    }

    // Auto excerpt from content
    public function getExcerptTextAttribute(): string
    {
        if (!empty($this->excerpt)) return $this->excerpt;
        return Str::limit(strip_tags($this->content ?? ''), 150);
    }

    // Image URL
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('assets/images/blog/1.jpg');
        if (str_starts_with($this->image, 'http')) return $this->image;
        if (str_starts_with($this->image, 'storage/')) return asset($this->image);
        return asset('storage/' . $this->image);
    }
}