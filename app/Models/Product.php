<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_code',
        'product_name',
        'category',
        'unit',
        'selling_price',
        'cost_price',
        'current_stock',
        'reorder_level',
        'description',
        'status',
    ];

    public function salesInvoiceLines()
    {
        return $this->hasMany(SalesInvoiceLine::class);
    }

    public function getProfitMarginAttribute()
    {
        return $this->selling_price - $this->cost_price;
    }
}