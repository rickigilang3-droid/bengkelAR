<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceApprovalItem extends Model
{
    protected $table = 'service_approval_items';

    protected $fillable = [
        'service_queue_id',
        'inventory_id',
        'requested_by',
        'item_name',
        'estimated_cost',
        'quantity',
        'odometer_km',
        'interval_km',
        'notes',
        'status',
        'responded_at',
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'responded_at' => 'datetime',
        'quantity' => 'integer',
        'odometer_km' => 'integer',
        'interval_km' => 'integer',
    ];

    public function serviceQueue(): BelongsTo
    {
        return $this->belongsTo(ServiceQueue::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
