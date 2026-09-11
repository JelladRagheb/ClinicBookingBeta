<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $doctor_profile_id
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string|null $start_time
 * @property string|null $end_time
 * @property string|null $reason
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DoctorProfile $doctor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereDoctorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorTimeOff whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DoctorTimeOff extends Model
{
    protected $fillable = [
        'doctor_profile_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'reason',
        'type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('end_date', '>=', now()->toDateString());
    }

    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString());
    }
}
