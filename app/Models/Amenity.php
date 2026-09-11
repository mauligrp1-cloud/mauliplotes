<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'short_description', 'is_active'];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_amenity');
    }
}
