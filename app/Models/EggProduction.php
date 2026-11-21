<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EggProduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'farm_id',
        'section_id',
        'batch_id',
        'production_date',
        'shift',
        'produced_eggs',
        'rejected_eggs',
        'saleable_eggs',
        'total_weight_kg',
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

    public function batch()
    {
        return $this->belongsTo(PoultryBatch::class, 'batch_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}