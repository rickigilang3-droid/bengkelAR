<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property int $vehicle_id
 * @property string $complaint
 * @property string $status
 * @property string $priority
 * @property \Illuminate\Support\Carbon|null $entered_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property string|null $notes
 */
class ServiceQueue extends Model
{
    protected $table = 'service_queues';

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'complaint',
        'status',
        'priority',
        'entered_at',
        'estimated_completion_at',
        'completed_at',
        'notes',
        'odometer_km',
    ];

    protected $casts = [
        'odometer_km' => 'integer',
        'entered_at' => 'datetime',
        'estimated_completion_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function chats()
    {
        return $this->hasMany(ServiceChat::class, 'service_queue_id')->orderBy('created_at', 'asc');
    }

    public function approvalItems(): HasMany
    {
        return $this->hasMany(ServiceApprovalItem::class, 'service_queue_id');
    }

    public function serviceHistory(): HasOne
    {
        return $this->hasOne(ServiceHistory::class, 'service_queue_id');
    }

    public function partReplacements(): HasMany
    {
        return $this->hasMany(PartReplacement::class, 'service_queue_id');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
