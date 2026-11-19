<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    protected $fillable = [
        'user_id',
        'vehicle_id',
        'part_name',
        'replaced_at',
        'odometer_km',
        'interval_km',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'replaced_at' => 'date',
            'odometer_km' => 'integer',
            'interval_km' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function getDueAtKmAttribute(): ?int
    {
        return $this->interval_km === null
            ? null
            : $this->odometer_km + $this->interval_km;
    }
}
