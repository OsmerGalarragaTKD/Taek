<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'event_id', // Nuevo campo para asociar el pago a un evento
        'payment_type',
        'month',
        'amount',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'month' => 'date', // Convierte 'month' en un objeto Carbon
        'payment_date' => 'datetime', // Esto ya lo tienes
    ];

    // Relación con el atleta
    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    // Relación con el evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
