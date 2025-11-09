<?php

namespace App\Models;

use App\Traits\FillsCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutpatientCenter extends Model
{
    use FillsCreatedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'email',
        'phone',
        'responsible',
        'address',
        'capacity',
        'current_occupancy',
        'is_active',
        'dni',
        'community_id',
    ];

    protected $casts = [
        'capacity' => 'integer',
        'current_occupancy' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'deleted_at',
        'updated_at',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function medicalResources()
    {
        return $this->hasMany(MedicalResource::class);
    }

    public function medicationRequests()
    {
        return $this->hasMany(MedicationRequest::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
