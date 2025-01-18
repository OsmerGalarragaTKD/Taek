<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeltGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'level',
        'name',
        'color',
        'description',
    ];

    public function athletes()
    {
        return $this->hasMany(AthleteGrade::class, 'grade_id');
    }
}
