<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceReview extends Model
{
    protected $table = 'service_reviews';

    protected $fillable = [
        'service_history_id',
        'user_id',
        'rating',
        'mechanic_rating',
        'comment',
        'admin_reply',
    ];

    public function serviceHistory(): BelongsTo
    {
        return $this->belongsTo(ServiceHistory::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

