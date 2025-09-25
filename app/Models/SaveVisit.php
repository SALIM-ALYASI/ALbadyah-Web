<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveVisit extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['country','city'];
}
