<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutpatientCenter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'email',
        'phone',
        'responsible',
        'address',
        'capacity',
        'current_occupancy',
        'is_active',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];
}
