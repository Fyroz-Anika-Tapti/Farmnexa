<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'item_code',
        'item_name',
        'category',
        'unit',
        'opening_stock',
        'current_stock',
        'average_cost',   
        'stock_value',    
        'reorder_level',
        'purchase_price',
        'description',
        'status',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * মোট স্টক ভ্যালু
     */
    public function getStockValueAttribute()
    {
        return ($this->current_stock ?? 0) * ($this->purchase_price ?? 0);
    }

    /**
     * স্টক কম কিনা?
     */
    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= $this->reorder_level;
    }

    /**
 * Add stock with weighted average cost
 */
public function addStock($quantity, $unitPrice)
{
    $oldValue    = $this->current_stock * $this->average_cost;
    $newValue    = $quantity * $unitPrice;
    $newQty      = $this->current_stock + $quantity;
    $newAvgCost  = $newQty > 0 ? ($oldValue + $newValue) / $newQty : 0;

    $this->update([
        'current_stock' => $newQty,
        'average_cost'  => $newAvgCost,
        'stock_value'   => $newQty * $newAvgCost,
    ]);
}

/**
 * Remove stock (keeps average cost same)
 */
public function removeStock($quantity)
{
    $newQty = max(0, $this->current_stock - $quantity);

    $this->update([
        'current_stock' => $newQty,
        'stock_value'   => $newQty * $this->average_cost,
    ]);
}


}