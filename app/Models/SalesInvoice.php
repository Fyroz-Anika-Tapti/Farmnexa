<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id', 'customer_id', 'invoice_number', 'invoice_date', 'due_date',
        'subtotal', 'tax_amount', 'discount_amount', 'total_amount',
        'paid_amount', 'due_amount', 'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function lines()
    {
        return $this->hasMany(SalesInvoiceLine::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function payments()
   {
    return $this->hasMany(CustomerPayment::class);
   }
}