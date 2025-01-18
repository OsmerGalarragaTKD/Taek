<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteRepresentatives extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'representative_id',
        'relationship',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    // Relación con el atleta
    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    // Relación con el representante
    public function representative()
    {
        return $this->belongsTo(Representative::class);
    }
}
