<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reminder extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'type',
        'title',
        'description',
        'reminder_date',
        'notify_days_before',
        'is_sent',
        'status',
    ];

    protected $casts = [
        'reminder_date' => 'date',
        'is_sent' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reminder_date', '>=', now())
                     ->where('status', 'active')
                     ->orderBy('reminder_date');
    }
}
