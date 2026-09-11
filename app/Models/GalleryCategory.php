<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class GalleryCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];
    protected $table = 'gallery_categories';

    public static function boot()
    {
        parent::boot();
        static::creating(function ($cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name);
            }
        });
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class, 'category_id');
    }

    // Direct items through the galleries relation
    public function items()
    {
        return $this->hasManyThrough(GalleryItem::class, Gallery::class, 'category_id', 'gallery_id');
    }
}
