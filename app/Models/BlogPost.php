<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    protected $table = 'blog_posts';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'featured_image',
        'body',
        'created_by',
        'author_name',
        'author_role',
        'author_avatar',
        'published_at',
        'status',
        'is_featured',
        'reading_time',
        'views_count',
        'related_project_ids',
        'enable_cta_box',
        'cta_heading',
        'cta_description',
        'cta_button_text',
        'cta_button_url',
        'meta_title',
        'focus_keyword',
        'meta_description',
        'canonical_url',
        'og_image',
        'og_title',
        'og_description',
        'robots',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'enable_cta_box' => 'boolean',
        'related_project_ids' => 'array',
        'views_count' => 'integer',
        'reading_time' => 'integer',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_category');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag');
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->featured_image);
    }

    public function getOgImageUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->og_image ?: $this->featured_image);
    }

    public function getAuthorAvatarUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->author_avatar);
    }

    public function getAuthorDisplayNameAttribute(): string
    {
        if (!empty($this->author_name)) {
            return $this->author_name;
        }
        return $this->author ? $this->author->name : 'Mauli Infra Research Team';
    }

    public function getAuthorDisplayRoleAttribute(): string
    {
        if (!empty($this->author_role)) {
            return $this->author_role;
        }
        return 'Plotted Development Expert & Advisory';
    }

    public function getEstimatedReadingTimeAttribute(): int
    {
        if (!empty($this->reading_time) && $this->reading_time > 0) {
            return $this->reading_time;
        }

        $wordCount = str_word_count(strip_tags($this->body ?? ''));
        return max(1, (int) ceil($wordCount / 200));
    }

    public function getRelatedProjectsListAttribute()
    {
        if (empty($this->related_project_ids) || !is_array($this->related_project_ids)) {
            return collect();
        }

        return Project::whereIn('id', $this->related_project_ids)
            ->where('is_published', true)
            ->with(['location'])
            ->get();
    }

    public static function normalizeMediaUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        $img = str_replace('\\', '/', trim($path));

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
            if (preg_match('#^https?://[^/]+/storage/(.+)$#i', $img, $matches)) {
                return '/storage/' . $matches[1];
            }
            return $img;
        }

        $trimmed = ltrim($img, '/');

        if (str_starts_with($trimmed, 'storage/')) {
            return '/' . $trimmed;
        }

        return '/storage/' . $trimmed;
    }
}
