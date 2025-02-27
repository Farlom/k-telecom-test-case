<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property integer $equipment_type_id
 * @property integer $serial_number
 * @property string $desc
 */
class Equipment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'equipment_type_id',
        'serial_number',
        'desc',
    ];

    public function equipmentType(): BelongsTo
    {
        return $this->belongsTo(EquipmentType::class);
    }
}
