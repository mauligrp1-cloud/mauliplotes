<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'template',
        'content',
        'featured_image',
        'is_published',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'robots',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(PageSection::class)->orderBy('sort_order');
    }

    public function activeSections()
    {
        return $this->hasMany(PageSection::class)->where('is_active', true)->orderBy('sort_order');
    }
}
