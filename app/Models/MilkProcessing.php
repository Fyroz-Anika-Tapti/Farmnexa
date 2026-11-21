<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProcessing extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'section_id',
        'processing_date',
        'product_name',
        'input_milk_liters',
        'output_quantity',
        'unit',
        'processing_cost',
        'selling_price_per_unit',
        'notes',
        'recorded_by',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * মোট বিক্রয়মূল্য (output_quantity × selling_price_per_unit)
     */
    public function getTotalValueAttribute()
    {
        return ($this->output_quantity ?? 0) * ($this->selling_price_per_unit ?? 0);
    }

    /**
     * মোট খরচ (দুধের মূল্য বাদে শুধু processing cost)
     */
    public function getTotalCostAttribute()
    {
        return $this->processing_cost ?? 0;
    }
}