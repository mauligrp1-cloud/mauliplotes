<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'gallery_id', 'project_id', 'image', 'title',
        'alt_text', 'caption', 'sort_order', 'is_active'
    ];
    protected $table = 'gallery_items';
    protected $casts = ['is_active' => 'boolean'];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return '';
        }

        $img = $this->image;

        // Already a full external URL
        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            // Fix localhost absolute URLs → relative
            if (preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?(/storage/.+)$#', $img, $matches)) {
                return $matches[3];
            }
            return $img;
        }

        // Already starts with /storage/ — return as-is
        if (str_starts_with($img, '/storage/')) {
            return $img;
        }

        // Relative path like "uploads/2026/09/..." — prepend /storage/
        return '/storage/' . ltrim(str_replace('\\', '/', $img), '/');
    }
}
