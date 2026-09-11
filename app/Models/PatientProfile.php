<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $date_of_birth
 * @property string|null $gender
 * @property string|null $blood_type
 * @property numeric|null $height_cm
 * @property numeric|null $weight_kg
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $emergency_contact_relation
 * @property string|null $insurance_provider
 * @property string|null $insurance_number
 * @property \Illuminate\Support\Carbon|null $insurance_expiry
 * @property int|null $preferred_location_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Consultation> $consultations
 * @property-read int|null $consultations_count
 * @property-read mixed $age
 * @property-read mixed $bmi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MedicalHistory> $medicalHistories
 * @property-read int|null $medical_histories_count
 * @property-read \App\Models\Location|null $preferredLocation
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prescription> $prescriptions
 * @property-read int|null $prescriptions_count
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VitalSign> $vitalSigns
 * @property-read int|null $vital_signs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereBloodType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereEmergencyContactName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereEmergencyContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereEmergencyContactRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereHeightCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereInsuranceExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereInsuranceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereInsuranceProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile wherePreferredLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PatientProfile whereWeightKg($value)
 * @mixin \Eloquent
 */
class PatientProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'blood_type',
        'height_cm',
        'weight_kg',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'insurance_provider',
        'insurance_number',
        'insurance_expiry',
        'preferred_location_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'height_cm' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'insurance_expiry' => 'date',
        'insurance_number' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function preferredLocation()
    {
        return $this->belongsTo(Location::class, 'preferred_location_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'user_id');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function medicalHistories()
    {
        return $this->hasMany(MedicalHistory::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth?->age;
    }

    public function getBmiAttribute()
    {
        if ($this->height_cm && $this->weight_kg) {
            $heightM = $this->height_cm / 100;
            return round($this->weight_kg / ($heightM * $heightM), 2);
        }
        return null;
    }
}
