<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name', 'customer_phone', 'customer_email',
        'lead_id', 'project_id', 'assigned_to', 'visit_date', 'visit_time',
        'status', 'message', 'internal_notes'
    ];
    protected $table = 'site_visits';

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
