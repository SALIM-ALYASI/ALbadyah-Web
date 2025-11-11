<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitAggregate extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'country',
        'city',
        'path',
        'visits_count',
        'unique_visits_count',
    ];

    protected $casts = [
        'date' => 'date',
        'visits_count' => 'integer',
        'unique_visits_count' => 'integer',
    ];
}

