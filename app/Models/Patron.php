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
        'name',
        'email',
        'contact_number',
    ];
}


