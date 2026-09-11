<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'original_filename',
        'stored_filename',
        'file_path',
        'mime_type',
        'file_size',
        'width',
        'height',
        'type',
        'alt_text',
        'title',
        'uploaded_by',
    ];

    protected $appends = ['url', 'formatted_size', 'is_image'];

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the accessible public URL for the media asset
     */
    public function getUrlAttribute(): string
    {
        if (empty($this->file_path)) {
            return '';
        }

        // If stored path is an external URL, return directly (cleaning any accidental localhost port mismatches)
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            if (preg_match('#^https?://(localhost|127\.0\.0\.1)(:\d+)?/storage/(.+)$#', $this->file_path, $matches)) {
                return '/storage/' . $matches[3];
            }
            return $this->file_path;
        }

        $cleanPath = ltrim(str_replace('\\', '/', $this->file_path), '/');
        if (str_starts_with($cleanPath, 'storage/')) {
            return '/' . $cleanPath;
        }

        return '/storage/' . $cleanPath;
    }

    /**
     * Human-readable file size format
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = (int) $this->file_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' KB';
        }
        return $bytes . ' B';
    }

    /**
     * Check if asset is an image
     */
    public function getIsImageAttribute(): bool
    {
        return $this->type === 'image' || str_starts_with($this->mime_type ?? '', 'image/');
    }

    /**
     * Search scope by filename, title, or alt text
     */
    public function scopeSearch($query, ?string $term)
    {
        if (!empty($term)) {
            $query->where(function ($q) use ($term) {
                $q->where('original_filename', 'like', "%{$term}%")
                  ->orWhere('title', 'like', "%{$term}%")
                  ->orWhere('alt_text', 'like', "%{$term}%");
            });
        }
        return $query;
    }

    /**
     * Filter by type scope
     */
    public function scopeOfType($query, ?string $type)
    {
        if (!empty($type) && $type !== 'all') {
            $query->where('type', $type);
        }
        return $query;
    }
}
