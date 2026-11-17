<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id',
        'customer_name',
        'customer_phone',
        'sale_date',
        'carrying_value',
        'selling_price',
        'proceeds_received',
        'status',
        'notes',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}