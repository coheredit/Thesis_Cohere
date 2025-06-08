<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = 'inquiry';

    protected $fillable = [
        'patron_id', 'event_type', 'theme_motif', 'venue', 'other_venue',
        'other_event_type', 'other_theme_motif', 'message', 'date', 'time', 'status'
    ];
}
