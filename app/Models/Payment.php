<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'acc_id',
        'acc_details',
        'user_id',
        'space_id',
        'subspace_id',
        'booking_id',
        'gateway',
        'note',
        'status',
        'api_cal_counter',
    ];

    protected function casts(): array
    {
        return [
            'acc_details' => 'array',
            'acc_id' => 'integer',
            'api_cal_counter' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    public function subSpace(): BelongsTo
    {
        return $this->belongsTo(SubSpace::class, 'subspace_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }
}
