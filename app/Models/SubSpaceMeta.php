<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubSpaceMeta extends Model
{
    use HasFactory;

    protected $table = 'subspace_meta';

    protected $fillable = [
        'subspace_id',
        'key',
        'value',
        'group',
        'order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'json',
            'order' => 'integer',
            'status' => Status::class,
        ];
    }

    public function subSpace(): BelongsTo
    {
        return $this->belongsTo(SubSpace::class, 'subspace_id');
    }
}
