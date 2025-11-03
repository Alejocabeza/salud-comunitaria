<?php

namespace App\Models;

use App\Traits\FillsCreatedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Community extends Model
{
    use FillsCreatedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'number_of_house',
        'number_of_people',
    ];

    protected $hidden = [
        'created_by',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function outpatientCenters()
    {
        return $this->hasMany(OutpatientCenter::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
