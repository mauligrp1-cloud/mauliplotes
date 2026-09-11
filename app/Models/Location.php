<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'short_description', 'detailed_description', 'why_invest_here',
        'connectivity', 'infrastructure', 'latitude', 'longitude',
        'hero_image', 'mobile_hero', 'featured', 'is_active',
        'meta_title', 'meta_description', 'canonical_url'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function nearbyPlaces()
    {
        return $this->hasMany(LocationNearbyPlace::class);
    }
}
