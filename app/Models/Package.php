<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $table = 'packages';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'image_2_path', 
        'image_3_path',
        'inclusions'
    ];

    protected $casts = [
        'inclusions' => 'array',
        'price' => 'decimal:2'
    ];
}