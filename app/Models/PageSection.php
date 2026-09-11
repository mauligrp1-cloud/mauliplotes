<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_id',
        'section_key',
        'section_name',
        'title',
        'subtitle',
        'content',
        'image',
        'secondary_image',
        'button_text',
        'button_url',
        'secondary_button_text',
        'secondary_button_url',
        'options',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return '';
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            if (preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/(.+)$#', $this->image, $matches)) {
                return '/storage/' . $matches[3];
            }
            return $this->image;
        }
        return '/storage/' . ltrim(str_replace('\\', '/', $this->image), '/');
    }

    public function getSecondaryImageUrlAttribute(): string
    {
        if (empty($this->secondary_image)) {
            return '';
        }
        if (str_starts_with($this->secondary_image, 'http://') || str_starts_with($this->secondary_image, 'https://')) {
            if (preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/(.+)$#', $this->secondary_image, $matches)) {
                return '/storage/' . $matches[3];
            }
            return $this->secondary_image;
        }
        return '/storage/' . ltrim(str_replace('\\', '/', $this->secondary_image), '/');
    }
}
