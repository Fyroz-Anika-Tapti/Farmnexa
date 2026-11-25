<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'section_id',
        'cost_centre_id',
        'animal_code',
        'animal_type',
        'breed',
        'sex',
        'date_of_birth',
        'purchase_date',
        'purchase_value',
        'source_type',
        'status',
        'notes'
    ];
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function weights()
    {
    return $this->hasMany(AnimalWeight::class);
    }

    public function healthEvents()
    {
    return $this->hasMany(AnimalHealthEvent::class);
    }
    public function feedRecords()
{
    return $this->hasMany(AnimalFeedRecord::class);
}

public function expenses()
{
    return $this->hasMany(AnimalExpense::class);
}

public function sale()
{
    return $this->hasOne(AnimalSale::class)->latestOfMany();
}

public function getTotalFeedCostAttribute()
{
    return $this->feedRecords()->sum('total_cost');
}

public function getTotalMedicineCostAttribute()
{
    return $this->healthEvents()->sum('medicine_cost');
}

public function getTotalOtherExpenseAttribute()
{
    return $this->expenses()->sum('amount');
}

public function getTotalCostAttribute()
{
    return ($this->purchase_value ?? 0)
         + $this->total_medicine_cost
         + $this->total_feed_cost
         + $this->total_other_expense;
}

public function getProfitAttribute()
{
    $salePrice = $this->sale->selling_price ?? 0;
    return $salePrice - $this->total_cost;
}
}
