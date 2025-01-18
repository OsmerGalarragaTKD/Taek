<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'athlete_id',
        'date',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class); // Asegúrate de que el nombre del modelo sea ClassModel
    }

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }
}
