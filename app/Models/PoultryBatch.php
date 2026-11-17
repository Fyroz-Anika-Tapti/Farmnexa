<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PoultryBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'section_id',
        'cost_centre_id',
        'batch_code',
        'bird_type',
        'breed',
        'opening_birds',
        'placement_date',
        'source_supplier',
        'status',
        'notes',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function costCentre()
    {
        return $this->belongsTo(CostCentre::class);
    }
    public function dailyActivities()
    {
        return $this->hasMany(PoultryDailyActivity::class, 'batch_id');
    }
}