<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'details',
        'ip_address',
    ];

    protected $casts = [
        'details' => 'array',
    ];
}
