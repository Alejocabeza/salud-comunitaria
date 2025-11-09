<?php

namespace App\Models;

use App\Traits\FillsCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use FillsCreatedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialty',
        'dni',
        'address',
        'registration_number',
        'is_active',
        'created_by',
        'outpatient_center_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'created_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function outpatientCenter(): BelongsTo
    {
        return $this->belongsTo(OutpatientCenter::class);
    }
}
