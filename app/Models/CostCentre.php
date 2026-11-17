<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostCentre extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'section_id',
        'centre_code',
        'centre_name',
        'description',
        'status',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}