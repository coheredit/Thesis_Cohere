<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $table = 'availabilities';

    protected $fillable = ['date', 'status'];

    protected $casts = [
        'date' => 'date', 

    ];
}
