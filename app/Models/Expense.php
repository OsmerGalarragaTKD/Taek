<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'amount',
        'expense_date',
        'category',
        'description',
        'payment_method',
        'receipt_url',
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
