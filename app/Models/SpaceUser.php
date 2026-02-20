<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SpaceUser extends Pivot
{
    protected $table = 'space_user';
    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $fillable = [
        'user_id',
        'space_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
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
}
