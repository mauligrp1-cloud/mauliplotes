<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectRERA extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'phase', 'rera_number', 'rera_url', 'status', 'approval_authority', 'additional_legal_information', 'sort_order'];
    protected $table = 'project_rera_registrations';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
