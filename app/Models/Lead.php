<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'phone', 'email', 'project_id', 'location_id', 'budget_range',
        'source', 'assigned_to', 'status', 'original_message',
        'landing_page', 'referrer', 'utm_source', 'utm_medium', 'utm_campaign',
        'utm_term', 'utm_content'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class);
    }

    public function siteVisits()
    {
        return $this->hasMany(SiteVisit::class);
    }
}
