<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectNearbyPlace extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'category', 'icon', 'place_name', 'distance', 'travel_time', 'description', 'sort_order'];
    protected $table = 'project_nearby_places';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
