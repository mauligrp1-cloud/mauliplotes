<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use HasFactory;

    protected $fillable = ['old_url', 'new_url', 'redirect_type', 'is_active', 'hits', 'last_accessed_at'];
    protected $casts = [
        'last_accessed_at' => 'datetime',
    ];
}
