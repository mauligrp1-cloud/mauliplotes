<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationNearbyPlace extends Model
{
    use HasFactory;

    protected $fillable = ['location_id', 'category', 'place_name', 'distance', 'travel_time', 'description', 'sort_order'];
    protected $table = 'location_nearby_places';

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
