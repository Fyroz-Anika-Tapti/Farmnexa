<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Animal extends Model
{
    use SoftDeletes;

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
}