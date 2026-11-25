<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalFeedRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id', 'inventory_item_id', 'stock_movement_id',
        'feed_date', 'quantity', 'unit', 'unit_price',
        'total_cost', 'notes', 'recorded_by',
    ];

    protected $casts = ['feed_date' => 'date'];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function stockMovement()
    {
        return $this->belongsTo(StockMovement::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}