<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'farm_id',
        'movement_date',
        'movement_type',
        'quantity',
        'unit_price',
        'total_value',
        'stock_before',
        'stock_after',
        'reference_type',
        'reference_no',
        'notes',
        'recorded_by',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}