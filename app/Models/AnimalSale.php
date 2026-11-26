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
        'profit_percentage',
        'selling_price',
        'profit_amount',
        'proceeds_received',
        'status',
        'notes',
    ];
    
     protected $casts = [
        'sale_date' => 'date',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}