<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'name',
        'schedule',
        'instructor_id',
        'max_capacity',
        'level_required',
        'status',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
