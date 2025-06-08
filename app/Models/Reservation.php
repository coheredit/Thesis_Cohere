<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';

    public $timestamps = false; // Assuming you’re using time_created/time_updated

    protected $fillable = [
        'inquiry_id',
        // add other fields you want to allow mass assignment
    ];
}
