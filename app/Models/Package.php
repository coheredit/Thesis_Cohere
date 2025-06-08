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
        'images', // use this instead of image_path
    ];

    protected $casts = [
        'images' => 'array', // decode JSON automatically
    ];
}
