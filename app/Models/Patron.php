<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patron extends Model
{
    protected $table = 'patron';
    protected $primaryKey = 'patron_id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'contact_number',
        // etc...
    ];
}


