<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'venue_id',
        'start_date',
        'end_date',
        'registration_deadline',
        'description',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'registration_deadline' => 'date',
    ];

    // Relación con la sede (venue)
    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    // Relación con las categorías del evento (a través de EventCategory)
    public function eventCategories()
    {
        return $this->hasMany(EventCategory::class);
    }

    // Relación con las categorías (a través de EventCategory)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'event_categories', 'event_id', 'category_id');
    }

    // Relación con los pagos asociados al evento
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
