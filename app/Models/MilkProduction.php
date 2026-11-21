<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MilkProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'section_id',
        'animal_id',
        'production_group_id',
        'production_date',
        'shift',
        'produced_liters',
        'rejected_liters',
        'saleable_liters',
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

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}