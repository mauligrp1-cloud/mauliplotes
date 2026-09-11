<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'type', 'image_path', 'alt_text', 'caption', 'sort_order'];
    protected $table = 'project_images';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return '';
        }

        $img = str_replace('\\', '/', trim($this->image_path));

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
