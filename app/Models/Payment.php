<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reserve_id',
        'reference_code',
        'receipt_path',
        'status',
    ];

    public $timestamps = false; // If your table uses time_created/time_updated instead of created_at/updated_at
}
