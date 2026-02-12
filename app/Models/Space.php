<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Space extends Model
{
    use HasFactory, SoftDeletes;

    public const META_FIELDS = [
        'logo' => ['group' => null, 'order' => 1],
        'featured_image' => ['group' => 'images', 'order' => 1],
        'images' => ['group' => 'images', 'order' => 2],
        'abstract' => ['group' => 'content', 'order' => 1],
        'content' => ['group' => 'content', 'order' => 2],
    ];

    public const META_KEYS = [
        'logo',
        'featured_image',
        'images',
        'abstract',
        'content',
    ];

    protected $fillable = [
        'title',
        'slug',
        'order',
        'note',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'order' => 'integer',
        ];
    }

    public function spaceMetas(): HasMany
    {
        return $this->hasMany(SpaceMeta::class, 'space_id');
    }

    public function metaValue(string $key): mixed
    {
        return $this->spaceMetas->firstWhere('key', $key)?->value;
    }

    public function setMetaValues(array $values): void
    {
        foreach ($values as $key => $value) {
            if (! array_key_exists($key, self::META_FIELDS)) {
                continue;
            }

            $meta = self::META_FIELDS[$key];

            $this->spaceMetas()->updateOrCreate(
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
