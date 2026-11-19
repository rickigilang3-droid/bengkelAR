<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    protected $table = 'inventories';

    protected $fillable = [
        'part_code',
        'name',
        'category',
        'stock',
        'min_stock',
        'purchase_price',
        'selling_price',
        'unit',
        'supplier',
        'image_url',
        'description',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
    ];

    public function replacements(): HasMany
    {
        return $this->hasMany(PartReplacement::class);
    }
}

