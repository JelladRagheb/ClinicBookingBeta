<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property array<array-key, mixed> $name
 * @property string $slug
 * @property array<array-key, mixed>|null $description
 * @property string|null $icon
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DoctorProfile> $doctors
 * @property-read int|null $doctors_count
 * @property-read mixed $translated_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Specialty whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Specialty extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'is_active' => 'boolean',
    ];

    public function doctors()
    {
        return $this->belongsToMany(DoctorProfile::class, 'doctor_specialties')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['fr'] ?? '';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
