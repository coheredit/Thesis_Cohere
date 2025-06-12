<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table = 'reservation';

    public $timestamps = false; 

    protected $fillable = [
        'inquiry_id',
        'patron_id',
        'date',
        'time',
        'venue',
        'event_type',
        'theme_motif',
        'message',
        'status'
    ];
}
