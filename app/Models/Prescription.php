<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $prescription_number
 * @property int|null $consultation_id
 * @property int $patient_profile_id
 * @property int $doctor_profile_id
 * @property \Illuminate\Support\Carbon $issued_date
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Consultation|null $consultation
 * @property-read \App\Models\DoctorProfile $doctor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PrescriptionItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\PatientProfile $patient
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereConsultationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereDoctorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereIssuedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription wherePatientProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription wherePrescriptionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prescription whereUuid($value)
 * @mixin \Eloquent
 */
class Prescription extends Model
{
    protected $fillable = [
        'uuid',
        'prescription_number',
        'consultation_id',
        'patient_profile_id',
        'doctor_profile_id',
        'issued_date',
        'expiry_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($prescription) {
            if (empty($prescription->uuid)) {
                $prescription->uuid = (string) Str::uuid();
            }
            if (empty($prescription->prescription_number)) {
                $prescription->prescription_number = 'RX-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>=', now()->toDateString());
            });
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }
}
