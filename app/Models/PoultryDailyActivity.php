<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoultryDailyActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'activity_date',
        'mortality',
        'culling',
        'transfer_in',
        'transfer_out',
        'closing_birds',
        'notes',
        'recorded_by',
    ];

    public function batch()
    {
        return $this->belongsTo(PoultryBatch::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}