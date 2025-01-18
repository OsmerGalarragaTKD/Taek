<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'grade_id',
        'date_achieved',
        'certificate_number',
        'examiner_notes',
    ];

    protected $casts = [
        'date_achieved' => 'date',
    ];

    // Relación con el atleta
    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    // Relación con el grado (cinturón)
    public function grade()
    {
        return $this->belongsTo(BeltGrade::class, 'grade_id');
    }
}
