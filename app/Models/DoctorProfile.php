<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $license_number
 * @property array<array-key, mixed>|null $bio
 * @property array<array-key, mixed>|null $education
 * @property int|null $experience_years
 * @property numeric $consultation_fee
 * @property int $cancellation_hours
 * @property int $reschedule_hours
 * @property bool $accepts_walk_ins
 * @property bool $is_available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Consultation> $consultations
 * @property-read int|null $consultations_count
 * @property-read mixed $primary_location
 * @property-read mixed $primary_specialty
 * @property-read mixed $translated_bio
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Location> $locations
 * @property-read int|null $locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prescription> $prescriptions
 * @property-read int|null $prescriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DoctorSchedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Specialty> $specialties
 * @property-read int|null $specialties_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DoctorTimeOff> $timeOffs
 * @property-read int|null $time_offs_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile available()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile byLocation($locationId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile bySpecialty($specialtyId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereAcceptsWalkIns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereBio($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereCancellationHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereConsultationFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereEducation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereExperienceYears($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereIsAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereRescheduleHours($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorProfile whereUserId($value)
 * @mixin \Eloquent
 */
class DoctorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'bio',
        'education',
        'experience_years',
        'consultation_fee',
        'cancellation_hours',
        'reschedule_hours',
        'accepts_walk_ins',
        'is_available',
    ];

    protected $casts = [
        'bio' => 'array',
        'education' => 'array',
        'consultation_fee' => 'decimal:2',
        'accepts_walk_ins' => 'boolean',
        'is_available' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialties')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class, 'doctor_locations')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function timeOffs()
    {
        return $this->hasMany(DoctorTimeOff::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function getTranslatedBioAttribute()
    {
        $locale = app()->getLocale();
        return $this->bio[$locale] ?? $this->bio['fr'] ?? '';
    }

    public function getPrimarySpecialtyAttribute()
    {
        return $this->relationLoaded('specialties')
            ? $this->specialties->firstWhere('pivot.is_primary', true)
            : $this->specialties()->wherePivot('is_primary', true)->first();
    }

    public function getPrimaryLocationAttribute()
    {
        return $this->relationLoaded('locations')
            ? $this->locations->firstWhere('pivot.is_primary', true)
            : $this->locations()->wherePivot('is_primary', true)->first();
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeBySpecialty($query, $specialtyId)
    {
        return $query->whereHas('specialties', function ($q) use ($specialtyId) {
            $q->where('specialties.id', $specialtyId);
        });
    }

    public function scopeByLocation($query, $locationId)
    {
        return $query->whereHas('locations', function ($q) use ($locationId) {
            $q->where('locations.id', $locationId);
        });
    }
}
