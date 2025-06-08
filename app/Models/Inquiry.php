<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $table = 'inquiry';

    protected $fillable = [
        'patron_id',
        'tracking_code',
        'event_type',
        'other_event_type',
        'theme_motif',
        'other_theme_motif',
        'venue',
        'other_venue',
        'event_date',
        'event_time',
        'message',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inquiry) {
            // Only set tracking_code if it's not already provided
            if (empty($inquiry->tracking_code)) {
                $inquiry->tracking_code = 'VS-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function patron()
    {
        return $this->belongsTo(Patron::class, 'patron_id');
    }
}
