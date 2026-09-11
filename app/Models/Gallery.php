<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'title', 'slug', 'description', 'is_active', 'sort_order'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title ?? 'gallery-' . time());
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(GalleryCategory::class, 'category_id');
    }

    public function items()
    {
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }

    public function activeItems()
    {
        return $this->hasMany(GalleryItem::class)->where('is_active', true)->orderBy('sort_order');
    }
}
