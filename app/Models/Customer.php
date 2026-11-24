<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'customer_code',
        'customer_name',
        'phone',
        'email',
        'address',
        'customer_type',
        'opening_balance',
        'current_balance',
        'status',
    ];

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    public function payments()
    {
        return $this->hasMany(CustomerPayment::class);
    }
}