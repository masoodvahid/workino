<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubSpace extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subspaces';

    public const META_FIELDS = [
        'feature_image' => ['group' => 'images', 'order' => 1],
        'images' => ['group' => 'images', 'order' => 2],
        'working_time' => ['group' => 'content', 'order' => 1],
        'abstract' => ['group' => 'content', 'order' => 2],
        'content' => ['group' => 'content', 'order' => 3],
    ];

    public const META_KEYS = [
        'feature_image',
        'images',
        'working_time',
        'abstract',
        'content',
    ];

    protected $fillable = [
        'space_id',
        'title',
        'slug',
        'type',
        'capacity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'capacity' => 'integer',
        ];
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    public function subSpaceMetas(): HasMany
    {
        return $this->hasMany(SubSpaceMeta::class, 'subspace_id');
    }

    public function metaValue(string $key): mixed
    {
        return $this->subSpaceMetas->firstWhere('key', $key)?->value;
    }

    public function setMetaValues(array $values): void
    {
        foreach ($values as $key => $value) {
            if (! array_key_exists($key, self::META_FIELDS)) {
                continue;
            }

            $meta = self::META_FIELDS[$key];

            $this->subSpaceMetas()->updateOrCreate(
                ['key' => $key],
                [
                    'value' => blank($value) ? null : $value,
                    'group' => $meta['group'],
                    'order' => $meta['order'],
                ],
            );
        }
    }
}
