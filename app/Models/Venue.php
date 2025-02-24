<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address_state',
        'address_city',
        'address_parish',
        'address_details',
        'founding_date',
        'director_name',
        'phone',
        'email',
        'status',
    ];

    protected $casts = [
        'founding_date' => 'date',
    ];

    public function athletes()
    {
        return $this->hasMany(Athlete::class);
    }
}
