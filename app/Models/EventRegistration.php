<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'athlete_id',
        'category_id',
        'payment_status',
        'status',
        'notes',
    ];

    protected $casts = [
        'registration_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
