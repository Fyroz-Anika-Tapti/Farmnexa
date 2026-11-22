<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'farm_id',
        'supplier_id',
        'gr_number',
        'received_date',
        'notes',
        'received_by',
    ];

    protected $casts = [
        'received_date' => 'date',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function lines()
    {
        return $this->hasMany(GoodsReceiptLine::class);
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}