<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'customer_image', 'project_id', 'short_quote', 'full_story', 'rating', 'owner_since', 'featured', 'sort_order', 'is_active'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
