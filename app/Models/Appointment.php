<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $uuid
 * @property string $appointment_number
 * @property int $patient_id
 * @property int $doctor_profile_id
 * @property int $location_id
 * @property int $appointment_type_id
 * @property \Illuminate\Support\Carbon $scheduled_date
 * @property string $scheduled_time
 * @property int $duration_minutes
 * @property string $status
 * @property bool $is_walk_in
 * @property bool $is_recurring
 * @property int|null $recurring_parent_id
 * @property string|null $notes
 * @property string|null $cancellation_reason
 * @property int|null $cancelled_by
 * @property \Illuminate\Support\Carbon|null $cancelled_at
 * @property \Illuminate\Support\Carbon|null $confirmed_at
 * @property \Illuminate\Support\Carbon|null $checked_in_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\AppointmentType $appointmentType
 * @property-read \App\Models\Consultation|null $consultation
 * @property-read \App\Models\DoctorProfile $doctor
 * @property-read \App\Models\Location $location
 * @property-read \App\Models\User $patient
 * @property-read \App\Models\Payment|null $payment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Appointment> $recurringChildren
 * @property-read int|null $recurring_children_count
 * @property-read Appointment|null $recurringParent
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment byStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment forDoctor($doctorProfileId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment forPatient($patientId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAppointmentNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereAppointmentTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCancellationReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCancelledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCancelledBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCheckedInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereDoctorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereIsWalkIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereRecurringParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereScheduledDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereScheduledTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Appointment withoutTrashed()
 * @mixin \Eloquent
 */
class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'appointment_number',
        'patient_id',
        'doctor_profile_id',
        'location_id',
        'appointment_type_id',
        'scheduled_date',
        'scheduled_time',
        'duration_minutes',
        'status',
        'is_walk_in',
        'is_recurring',
        'recurring_parent_id',
        'notes',
        'cancellation_reason',
        'cancelled_by',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'is_walk_in' => 'boolean',
        'is_recurring' => 'boolean',
        'cancelled_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'checked_in_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            if (empty($appointment->uuid)) {
                $appointment->uuid = (string) Str::uuid();
            }
            if (empty($appointment->appointment_number)) {
                $appointment->appointment_number = 'APT-' . date('Y') . '-' . str_pad(
                    static::whereYear('created_at', date('Y'))->count() + 1,
                    5,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function appointmentType()
    {
        return $this->belongsTo(AppointmentType::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function recurringParent()
    {
        return $this->belongsTo(Appointment::class, 'recurring_parent_id');
    }

    public function recurringChildren()
    {
        return $this->hasMany(Appointment::class, 'recurring_parent_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_date', '>=', now()->toDateString())
            ->whereIn('status', ['scheduled', 'confirmed']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForDoctor($query, $doctorProfileId)
    {
        return $query->where('doctor_profile_id', $doctorProfileId);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function canCancel()
    {
        if (!in_array($this->status, ['scheduled', 'confirmed'])) {
            return false;
        }

        $appointmentDateTime = $this->scheduled_date->setTimeFromTimeString($this->scheduled_time);
        $cancellationDeadline = $appointmentDateTime->subHours($this->doctor->cancellation_hours);

        return now()->lt($cancellationDeadline);
    }

    public function canReschedule()
    {
        if (!in_array($this->status, ['scheduled', 'confirmed'])) {
            return false;
        }

        $appointmentDateTime = $this->scheduled_date->setTimeFromTimeString($this->scheduled_time);
        $rescheduleDeadline = $appointmentDateTime->subHours($this->doctor->reschedule_hours);

        return now()->lt($rescheduleDeadline);
    }
}
