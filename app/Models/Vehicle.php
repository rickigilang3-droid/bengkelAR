<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_type',
        'brand',
        'model',
        'year',
        'color',
        'plate_number',
        'engine_number',
        'chassis_number',
        'description',
        'current_odometer_km',
    ];

    protected function casts(): array
    {
        return [
            'current_odometer_km' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function serviceQueues(): HasMany
    {
        return $this->hasMany(ServiceQueue::class);
    }

    public function serviceHistories(): HasMany
    {
        return $this->hasMany(ServiceHistory::class);
    }

    public function bookingRequests(): HasMany
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }
}
