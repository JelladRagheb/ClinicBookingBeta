<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $doctor_profile_id
 * @property int $location_id
 * @property int $day_of_week
 * @property string $start_time
 * @property string $end_time
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DoctorProfile $doctor
 * @property-read \App\Models\Location $location
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule forDay($dayOfWeek)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereDoctorProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DoctorSchedule whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_profile_id',
        'location_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }
}
