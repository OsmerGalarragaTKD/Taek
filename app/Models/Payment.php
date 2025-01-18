<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'amount',
        'payment_date',
        'payment_type',
        'payment_method',
        'status',
        'reference_number',
        'receipt_url',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }
}
