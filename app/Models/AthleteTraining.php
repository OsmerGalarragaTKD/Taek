<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AthleteTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'training_plan_id',
        'start_date',
        'completion_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'completion_date' => 'date',
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    public function trainingPlan()
    {
        return $this->belongsTo(TrainingPlan::class);
    }
}
