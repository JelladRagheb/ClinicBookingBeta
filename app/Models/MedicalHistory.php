<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $patient_profile_id
 * @property int $recorded_by
 * @property \Illuminate\Support\Carbon $recorded_at
 * @property string $category
 * @property string $title
 * @property string|null $description
 * @property string|null $severity
 * @property \Illuminate\Support\Carbon|null $onset_date
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PatientProfile $patient
 * @property-read \App\Models\User $recordedBy
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory byCategory($category)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereOnsetDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory wherePatientProfileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereRecordedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereRecordedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MedicalHistory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MedicalHistory extends Model
{
    protected $fillable = [
        'patient_profile_id',
        'recorded_by',
        'recorded_at',
        'category',
        'title',
        'description',
        'severity',
        'onset_date',
        'is_active',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'onset_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }
}
