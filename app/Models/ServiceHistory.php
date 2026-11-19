<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property int $vehicle_id
 * @property int|null $service_queue_id
 * @property string $service_type
 * @property string $description
 * @property \Illuminate\Support\Carbon $entered_at
 * @property \Illuminate\Support\Carbon $completed_at
 * @property string $total_cost
 * @property string|null $payment_method
 * @property string|null $payment_channel
 * @property string $payment_status
 * @property string|null $payment_proof_path
 * @property \Illuminate\Support\Carbon|null $payment_submitted_at
 * @property int|null $rating
 * @property string|null $notes
 * @property-read int $duration
 */
class ServiceHistory extends Model
{
    protected $table = 'service_histories';

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'service_queue_id',
        'service_type',
        'description',
        'entered_at',
        'completed_at',
        'total_cost',
        'payment_method',
        'payment_channel',
        'payment_status',
        'payment_proof_path',
        'payment_submitted_at',
        'rating',
        'notes',
    ];

    protected $casts = [
        'entered_at' => 'datetime',
        'completed_at' => 'datetime',
        'payment_submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function serviceQueue(): BelongsTo
    {
        return $this->belongsTo(ServiceQueue::class);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('completed_at', now()->month)
                     ->whereYear('completed_at', now()->year);
    }

    public function getDurationAttribute()
    {
        return $this->completed_at->diffInHours($this->entered_at);
    }
}
