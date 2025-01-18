<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'venue_id',
        'start_date',
        'end_date',
        'registration_deadline',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
