<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $consultation_id
 * @property int $patient_profile_id
 * @property int $recorded_by
 * @property \Illuminate\Support\Carbon $recorded_at
 * @property numeric|null $temperature_celsius
 * @property int|null $blood_pressure_systolic
 * @property int|null $blood_pressure_diastolic
 * @property int|null $heart_rate_bpm
 * @property int|null $respiratory_rate
 * @property numeric|null $oxygen_saturation
 * @property numeric|null $weight_kg
 * @property numeric|null $height_cm
 * @property numeric|null $bmi
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Consultation $consultation
 * @property-read mixed $blood_pressure
 * @property-read \App\Models\PatientProfile $patient
 * @property-read \App\Models\User $recordedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereBloodPressureDiastolic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereBloodPressureSystolic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereBmi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereConsultationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereHeartRateBpm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereHeightCm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereOxygenSaturation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign wherePatientProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereRecordedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereRecordedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereRespiratoryRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereTemperatureCelsius($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalSign whereWeightKg($value)
 * @mixin \Eloquent
 */
class VitalSign extends Model
{
    protected $fillable = [
        'consultation_id',
        'patient_profile_id',
        'recorded_by',
        'recorded_at',
        'temperature_celsius',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'heart_rate_bpm',
        'respiratory_rate',
        'oxygen_saturation',
        'weight_kg',
        'height_cm',
        'bmi',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'temperature_celsius' => 'decimal:2',
        'oxygen_saturation' => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'height_cm' => 'decimal:2',
        'bmi' => 'decimal:2',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getBloodPressureAttribute()
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return "{$this->blood_pressure_systolic}/{$this->blood_pressure_diastolic}";
        }
        return null;
    }
}
