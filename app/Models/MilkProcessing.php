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
        'milk_cost',             
        'notes',
        'recorded_by',
    ];

    protected $casts = ['processing_date' => 'date'];

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

    public function getTotalValueAttribute()
    {
        return ($this->output_quantity ?? 0) * ($this->selling_price_per_unit ?? 0);
    }

    public function getTotalCostAttribute()
    {
        return ($this->milk_cost ?? 0) + ($this->processing_cost ?? 0);
    }

    public function getProfitAttribute()
    {
        return $this->total_value - $this->total_cost;
    }
}