<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'activity_type',
        'description',
    ];

    /**
     * Relationship to the Admin who owns this activity.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
