<?php

namespace App\Models;

use App\Enums\BookingStatus;
use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'space_id',
        'code',
        'title',
        'description',
        'type',
        'start',
        'end',
        'limits',
        'applied_to',
        'priority',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => DiscountType::class,
            'start' => 'date',
            'end' => 'date',
            'limits' => 'integer',
            'applied_to' => 'array',
            'priority' => 'integer',
            'status' => BookingStatus::class,
        ];
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'space_id');
    }
}
