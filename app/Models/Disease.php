<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Disease extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'icd_code',
        'category',
        'description',
        'contagious',
        'severity',
        'active',
    ];

    protected $casts = [
        'contagious' => 'boolean',
        'active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Disease $disease) {
            if (empty($disease->slug)) {
                $disease->slug = Str::slug($disease->name);
            }
        });
    }

    public function patients(): BelongsToMany
    {
        return $this->belongsToMany(Patient::class, 'patient_disease')
            ->withPivot(['diagnosed_at', 'status', 'notes'])
            ->withTimestamps();
    }
}
