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
}