<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $location_id
 * @property array<array-key, mixed> $name
 * @property \Illuminate\Support\Carbon $date
 * @property bool $is_recurring
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $translated_name
 * @property-read \App\Models\Location|null $location
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday whereIsRecurring($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LocationHoliday whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LocationHoliday extends Model
{
    protected $fillable = [
        'location_id',
        'name',
        'date',
        'is_recurring',
    ];

    protected $casts = [
        'name' => 'array',
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function getTranslatedNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? $this->name['fr'] ?? '';
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }
}
