<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    protected $table = 'patron';
    protected $primaryKey = 'patron_id';
    public $incrementing = true;
    public $timestamps = false; // unless you have created_at/updated_at columns

    protected $fillable = [
        'first_name', 'last_name', 'email', 'contact_number', // add all fields you need
    ];
}

