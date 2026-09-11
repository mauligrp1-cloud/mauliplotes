<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'designation', 'photo', 'short_bio', 'full_bio', 'quote', 'sort_order', 'is_active'];
    protected $table = 'team_members';
}
