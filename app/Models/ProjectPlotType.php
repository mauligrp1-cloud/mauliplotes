<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPlotType extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'name', 'size_from', 'size_to', 'unit', 'price', 'availability', 'features', 'sort_order'];
    protected $table = 'project_plot_types';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
