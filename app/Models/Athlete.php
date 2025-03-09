<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    use HasFactory;

    protected $fillable = [
        'venue_id',
        'full_name',
        'identity_document',
        'nationality',
        'birth_date',
        'birth_place',
        'gender',
        'civil_status',
        'profession',
        'institution',
        'academic_level',
        'phone',
        'email',
        'social_media',
        'address_state',
        'address_city',
        'address_parish',
        'address_details',
        'medical_conditions',
        'allergies',
        'surgeries',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'height',
        'current_weight',
        'shirt_size',
        'pants_size',
        'shoe_size',
        'has_passport',
        'passport_number',
        'passport_expiry',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'passport_expiry' => 'date',
        'social_media' => 'array', // Si 'social_media' es un campo JSON
        'has_passport' => 'boolean',
    ];

    public function venue()
    {
        return $this->belongsTo(Venue::class, 'venue_id');
    }

    public function currentGrade()
    {
        return $this->hasOne(AthleteGrade::class)->latestOfMany();
    }

    public function grades()
    {
        return $this->hasMany(AthleteGrade::class);
    }

    // Relación con los representantes
    public function representatives()
    {
        return $this->hasMany(AthleteRepresentatives::class);
    }

    // Obtener el representante principal
    public function primaryRepresentative()
    {
        return $this->hasOne(AthleteRepresentatives::class)->where('is_primary', true);
    }

    public function athletesRepresenting()
    {
        return $this->hasMany(AthleteRepresentatives::class, 'representative_id', 'identity_document')
            ->whereHas('representative', function ($query) {
                $query->where('identity_document', $this->identity_document);
            });
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Athlete.php

    public function hasPaidEvent($eventId)
    {
        return $this->payments()
            ->where('payment_type', 'Event_Registration')
            ->where('status', 'Completed')
            ->exists();
    }
}
