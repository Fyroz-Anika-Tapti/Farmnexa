<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'animal_id', 'expense_date', 'expense_type', 'amount', 'description', 'recorded_by',
    ];

    protected $casts = ['expense_date' => 'date'];

    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}