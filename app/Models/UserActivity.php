<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'details'
    ];
    
    protected $casts = [
        'details' => 'array',
    ];
    
    /**
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Scope a query to only include activities of a specific action.
     */
    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }
    
    /**
     * Scope a query to only include activities from a specific date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
    
    /**
     * Log a user activity.
     */
    public static function log($userId, $action, $details = null)
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => $details,
        ]);
    }
}
