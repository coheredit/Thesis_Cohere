<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primaryKey = 'admin_id';

    public $timestamps = true;

    protected $fillable = [
        'email',
        'f_name',
        'l_name',
        'phone',
        'password',
        'profile_picture', 
    ];
}