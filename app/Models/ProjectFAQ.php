<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFAQ extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'question', 'answer', 'sort_order', 'is_active'];
    protected $table = 'project_faqs';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
