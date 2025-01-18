<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'athlete_id',
        'event_id',
        'content',
        'file_url',
        'status',
    ];

    protected $casts = [
        'generated_date' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(DocumentTemplate::class);
    }

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
