<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
     protected $fillable = [
        'farm_name',
        'farm_code',
        'location',
        'phone',
        'currency_code',
        'status',
    ];
}
