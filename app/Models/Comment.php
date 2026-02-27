<?php

namespace App\Models;

use App\Enums\CommentStatus;
use App\Enums\InteractableType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'parent_id',
        'content',
        'rating',
        'reply_to',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'type' => InteractableType::class,
            'parent_id' => 'integer',
            'rating' => 'integer',
            'reply_to' => 'integer',
            'status' => CommentStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reply_to');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(self::class, 'reply_to');
    }
}
