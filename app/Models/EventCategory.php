<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'category_id',
        'registration_fee',
        'additional_costs',
        'school_percentage',
    ];

    protected $casts = [
        'additional_costs' => 'array',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
