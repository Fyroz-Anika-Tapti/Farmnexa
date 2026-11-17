<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'record_date',
        'weight_kg',
        'notes',
        'recorded_by',
    ];

    /**
     * Relationship: Weight record belongs to an Animal.
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    /**
     * Relationship: Weight record was recorded by a User.
     */
    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}