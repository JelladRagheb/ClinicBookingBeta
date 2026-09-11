<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'start',
        'end',
        'color',
        'doctor_profile_id',
        'patient_profile_id'
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }
}
