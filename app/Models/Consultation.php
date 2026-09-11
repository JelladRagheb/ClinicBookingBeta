<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $appointment_id
 * @property int $patient_profile_id
 * @property int $doctor_profile_id
 * @property string|null $chief_complaint
 * @property array<array-key, mixed>|null $symptoms
 * @property string|null $diagnosis
 * @property string|null $treatment_plan
 * @property string|null $notes
 * @property bool $follow_up_required
 * @property int|null $follow_up_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Appointment $appointment
 * @property-read \App\Models\DoctorProfile $doctor
 * @property-read \App\Models\PatientProfile $patient
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prescription> $prescriptions
 * @property-read int|null $prescriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VitalSign> $vitalSigns
 * @property-read int|null $vital_signs_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereAppointmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereChiefComplaint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereDiagnosis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereDoctorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereFollowUpDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereFollowUpRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation wherePatientProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereSymptoms($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereTreatmentPlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Consultation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Consultation extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_profile_id',
        'doctor_profile_id',
        'chief_complaint',
        'symptoms',
        'diagnosis',
        'treatment_plan',
        'notes',
        'follow_up_required',
        'follow_up_days',
    ];

    protected $casts = [
        'symptoms' => 'array',
        'notes' => 'encrypted',
        'follow_up_required' => 'boolean',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
