<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $prescription_id
 * @property string $medication_name
 * @property string $dosage
 * @property string $frequency
 * @property int|null $duration_days
 * @property int|null $quantity
 * @property string|null $instructions
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Prescription $prescription
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereDosage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereInstructions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereMedicationName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem wherePrescriptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrescriptionItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id',
        'medication_name',
        'dosage',
        'frequency',
        'duration_days',
        'quantity',
        'instructions',
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
}
