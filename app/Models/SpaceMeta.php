<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpaceMeta extends Model
{
    use HasFactory;

    protected $table = 'space_meta';

    protected $fillable = [
        'space_id',
        'key',
        'value',
        'group',
        'order',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
            'order' => 'integer',
            'status' => Status::class,
        ];
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'space_id');
    }
}
