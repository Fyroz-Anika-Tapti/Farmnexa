<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'section_name',
        'section_type',
        'description',
        'status',
    ];

    /**
     * Relationship: Section belongs to a Farm.
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}