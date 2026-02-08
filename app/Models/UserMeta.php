<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMeta extends Model
{
    /** @use HasFactory<\Database\Factories\UserMetaFactory> */
    use HasFactory, SoftDeletes;

    protected $table = 'user_meta';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'uid',
        'key',
        'value',
        'is_encrypted',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_encrypted' => 'boolean',
            'status' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uid');
    }
}
