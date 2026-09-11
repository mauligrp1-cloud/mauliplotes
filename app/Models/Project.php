<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'project_code', 'location_id', 'address', 'project_type', 'status',
        'completion_year', 'short_description', 'hero_tagline', 'description',
        'overview_label', 'overview_title', 'overview_image', 'overview_image_alt',
        'overview_image_position', 'overview_punchline', 'overview_facts',
        'starting_price', 'price_unit', 'display_price', 'price_on_request', 'show_price',
        'bank_loan_text', 'legal_clearances_text', 'cta_instant_callback_text',
        'total_project_area', 'area_unit', 'total_plots',
        'featured_image', 'desktop_hero', 'mobile_hero', 'master_plan', 'layout_map',
        'location_map', 'location_map_heading', 'location_map_address', 'location_map_url',
        'brochure', 'walkthrough_video_url', 'video_heading', 'video_subtitle', 'video_description', 'video_thumbnail', 'video_features',
        'section_visibility', 'hero_badges', 'hero_cta', 'hero_info_card',
        'trust_strip', 'plot_configs_heading', 'plot_configs_description', 'plot_configs_cta_text',
        'amenities_heading', 'amenities_description', 'custom_amenities',
        'location_advantage_heading', 'location_advantage_subtext',
        'specifications_heading', 'specifications',
        'faqs_heading',
        'final_cta_heading', 'final_cta_description', 'final_cta_primary_text', 'final_cta_secondary_text', 'final_cta_image',
        'related_project_ids',
        'featured', 'sort_order', 'is_published', 'meta_title', 'meta_description', 'og_title', 'og_description', 'og_image', 'robots', 'canonical_url',
        'created_by', 'updated_by'
    ];

    protected $casts = [
        'section_visibility' => 'array',
        'hero_badges' => 'array',
        'hero_cta' => 'array',
        'hero_info_card' => 'array',
        'trust_strip' => 'array',
        'overview_facts' => 'array',
        'custom_amenities' => 'array',
        'specifications' => 'array',
        'video_features' => 'array',
        'related_project_ids' => 'array',
        'sort_order' => 'integer',
        'is_published' => 'boolean',
        'featured' => 'boolean',
        'price_on_request' => 'boolean',
        'show_price' => 'boolean',
    ];

    public function isSectionVisible(string $section, bool $default = true): bool
    {
        if (empty($this->section_visibility) || !is_array($this->section_visibility)) {
            return $default;
        }

        if (array_key_exists($section, $this->section_visibility)) {
            return (bool) $this->section_visibility[$section];
        }

        $aliases = [
            'trust' => 'trust_strip',
            'trust_strip' => 'trust',
            'plots' => 'plot_configs',
            'plot_configs' => 'plots',
            'plot_configurations' => 'plot_configs',
            'location' => 'location_advantage',
            'location_advantage' => 'location',
            'map' => 'location_map',
            'location_map' => 'map',
            'faq' => 'faqs',
            'faqs' => 'faq',
            'specs' => 'specifications',
            'specifications' => 'specs',
            'cta' => 'final_cta',
            'final_cta' => 'cta',
        ];

        if (isset($aliases[$section]) && array_key_exists($aliases[$section], $this->section_visibility)) {
            return (bool) $this->section_visibility[$aliases[$section]];
        }

        return $default;
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class);
    }

    public function plotTypes()
    {
        return $this->hasMany(ProjectPlotType::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'project_amenity');
    }

    public function reraRegistrations()
    {
        return $this->hasMany(ProjectRERA::class);
    }

    public function nearbyPlaces()
    {
        return $this->hasMany(ProjectNearbyPlace::class);
    }

    public function faqs()
    {
        return $this->hasMany(ProjectFAQ::class);
    }

    public function galleryItems()
    {
        return $this->hasMany(GalleryItem::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
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

    public function getFeaturedImageUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->featured_image);
    }

    public function getDesktopHeroUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->desktop_hero ?: $this->featured_image);
    }

    public function getMobileHeroUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->mobile_hero ?: ($this->desktop_hero ?: $this->featured_image));
    }

    public function getMasterPlanUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->master_plan ?: $this->layout_map);
    }

    public function getLayoutMapUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->layout_map);
    }

    public function getLocationMapUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->location_map);
    }

    public function getOverviewImageUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->overview_image ?: ($this->master_plan ?: ($this->images()->where('type', 'gallery')->first()->image_path ?? $this->featured_image)));
    }

    public function getFinalCtaImageUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->final_cta_image ?: ($this->desktop_hero ?: $this->featured_image));
    }

    public function getBrochureUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->brochure);
    }

    public function getVideoThumbnailUrlAttribute(): string
    {
        return self::normalizeMediaUrl($this->video_thumbnail ?: ($this->desktop_hero ?: $this->featured_image));
    }

    public function getVideoEmbedUrlAttribute(): string
    {
        $url = trim($this->walkthrough_video_url ?? '');
        if (empty($url)) {
            return '';
        }

        // YouTube URL formats: watch?v=ID, youtu.be/ID, embed/ID, shorts/ID
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/|youtube\.com\/shorts\/)([^"&?\/ ]{11})/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0&modestbranding=1';
        }

        // Vimeo URL formats: vimeo.com/ID
        if (preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/(?:[^\/]*)\/videos\/|album\/(?:\d+)\/video\/|video\/|)(\d+)/i', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1';
        }

        return $url;
    }

    public function getHeroBadgesListAttribute(): array
    {
        if (!empty($this->hero_badges) && is_array($this->hero_badges)) {
            $activeBadges = array_filter($this->hero_badges, function ($b) {
                return !empty($b['name']) && (!isset($b['active']) || (bool)$b['active'] === true || $b['active'] === 1 || $b['active'] === '1');
            });
            return array_values($activeBadges);
        }

        $badges = [];
        if ($this->featured) {
            $badges[] = ['name' => 'FEATURED', 'style' => 'bg-emerald-600/90 text-white', 'dot' => '#10B981', 'active' => true];
        }
        $primaryRera = $this->reraRegistrations->first();
        if ($primaryRera) {
            $badges[] = ['name' => 'RERA APPROVED: ' . $primaryRera->rera_number, 'style' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'dot' => '#10B981', 'active' => true];
        } else {
            $badges[] = ['name' => 'RERA APPROVED', 'style' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', 'dot' => '#10B981', 'active' => true];
        }
        $badges[] = ['name' => strtoupper($this->status ?? 'ACTIVE'), 'style' => 'bg-blue-500/20 text-blue-300 border-blue-500/30', 'dot' => '#3B82F6', 'active' => true];

        return $badges;
    }
}

