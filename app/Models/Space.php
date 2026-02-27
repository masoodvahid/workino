<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Space extends Model
{
    use HasFactory, SoftDeletes;

    public const META_FIELDS = [
        'city' => ['group' => null, 'order' => 1],
        'address' => ['group' => 'location', 'order' => 1],
        'postal_code' => ['group' => 'location', 'order' => 2],
        'logo' => ['group' => null, 'order' => 1],
        'location_neshan' => ['group' => 'location', 'order' => 3],
        'off_dates' => ['group' => 'calendar', 'order' => 1],
        'featured_image' => ['group' => 'images', 'order' => 1],
        'images' => ['group' => 'images', 'order' => 2],
        'social' => ['group' => 'social', 'order' => 1],
        'phones' => ['group' => 'phones', 'order' => 1],
        'abstract' => ['group' => 'content', 'order' => 1],
        'content' => ['group' => 'content', 'order' => 2],
    ];

    public const META_KEYS = [
        'city',
        'address',
        'postal_code',
        'logo',
        'location_neshan',
        'off_dates',
        'featured_image',
        'images',
        'social',
        'phones',
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

    public function subSpaces(): HasMany
    {
        return $this->hasMany(SubSpace::class, 'space_id');
    }

    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, SubSpace::class, 'space_id', 'subspace_id', 'id', 'id');
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'space_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'space_id');
    }

    public function spaceUsers(): HasMany
    {
        return $this->hasMany(SpaceUser::class, 'space_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'space_user', 'space_id', 'user_id')
            ->using(SpaceUser::class)
            ->withPivot(['id', 'status'])
            ->withTimestamps();
    }

    public function metaValue(string $key): mixed
    {
        $value = $this->spaceMetas->firstWhere('key', $key)?->value;

        return $value;
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
