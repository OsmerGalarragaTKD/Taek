<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'division',
        'age_group',
        'min_age',
        'max_age',
        'min_weight',
        'max_weight',
        'gender',
        'disability_type',
        'disability_class',
        'description',
    ];
}
