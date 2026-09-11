<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property array<array-key, mixed> $name
 * @property string $slug
 * @property array<array-key, mixed>|null $description
 * @property int $duration_minutes
 * @property numeric $default_fee
 * @property string $color
 * @property bool $requires_preparation
 * @property array<array-key, mixed>|null $preparation_instructions
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Appointment> $appointments
 * @property-read int|null $appointments_count
 * @property-read mixed $translated_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereDefaultFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType wherePreparationInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereRequiresPreparation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AppointmentType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AppointmentType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'duration_minutes',
        'default_fee',
        'color',
        'requires_preparation',
        'preparation_instructions',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'default_fee' => 'decimal:2',
        'requires_preparation' => 'boolean',
        'preparation_instructions' => 'array',
        'is_active' => 'boolean',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
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
