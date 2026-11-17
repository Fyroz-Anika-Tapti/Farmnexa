<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalHealthEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'event_type',
        'event_date',
        'diagnosis',
        'treatment_description',
        'medicine_cost',
        'veterinarian_name',
        'next_follow_up_date',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}