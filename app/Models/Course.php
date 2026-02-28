<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'instructors',
        'duration',
        'level',
        'price',
        'rating',
        'students',
        'description',
        'image',
        'topics',
        'start_date',
        'end_date',
    ];

    protected function casts(): array
    {
        return [
            'instructors' => 'array',
            'students' => 'array',
            'topics' => 'array',
            'price' => 'decimal:2',
            'rating' => 'decimal:1',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
}
