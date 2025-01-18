<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'identity_document',
        'nationality',
        'birth_date',
        'profession',
        'blood_type',
        'phone',
        'email',
        'social_media',
        'is_also_athlete',
        'has_passport',
        'passport_expiry',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'passport_expiry' => 'date',
        'social_media' => 'array', // Si 'social_media' es un campo JSON
        'is_also_athlete' => 'boolean',
        'has_passport' => 'boolean',
    ];

    public function athletes()
    {
        return $this->hasMany(AthleteRepresentatives::class);
    }

    // Si el representante también es atleta
    public function athleteProfile()
    {
        return $this->hasOne(Athlete::class, 'identity_document', 'identity_document')
            ->where('identity_document', '!=', null);
    }
}
