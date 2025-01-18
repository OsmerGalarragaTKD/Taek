<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'weight',
        'measurement_date',
        'notes',
    ];

    protected $casts = [
        'measurement_date' => 'date',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }
}
