<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'log_id';
    public $incrementing = false; 
    protected $keyType = 'string'; 
    
    protected $table = 'activity_log';

    protected $fillable = [
        'log_id', 
        'admin_id',
        'activity_type',
        'description',
        'inquiry_id',
        'reserve_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * Boot method to auto-generate log_id
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->log_id)) {
                $model->log_id = 'LOG_' . uniqid('', true);
            }
        });
    }

    /**
     * Relationship to the Admin who owns this activity.
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}