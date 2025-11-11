<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'fingerprint',
        'ip_hash',
        'user_agent',
        'country',
        'city',
        'path',
        'referer',
        'is_unique',
        'visited_at',
    ];

    protected $casts = [
        'is_unique' => 'boolean',
        'visited_at' => 'datetime',
    ];
}

