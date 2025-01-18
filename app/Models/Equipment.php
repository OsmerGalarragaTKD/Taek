<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'venue_id',
        'quantity',
        'status',
        'acquisition_date',
        'last_maintenance_date',
        'cost',
        'notes',
    ];

    protected $casts = [
        'acquisition_date' => 'date',
        'last_maintenance_date' => 'date',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }
}
