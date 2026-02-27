<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\BookingUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'subspace_id',
        'title',
        'description',
        'unit',
        'unit_rules',
        'base_price',
        'special_price',
        'start',
        'end',
        'priority',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'unit' => BookingUnit::class,
            'unit_rules' => 'array',
            'base_price' => 'integer',
            'special_price' => 'integer',
            'start' => 'date',
            'end' => 'date',
            'priority' => 'integer',
            'status' => BookingStatus::class,
        ];
    }

    public function subSpace(): BelongsTo
    {
        return $this->belongsTo(SubSpace::class, 'subspace_id');
    }
}
