<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const META_KEYS = [
        'national_id',
        'reg_number',
        'birth_day',
        'education',
        'major',
        'university',
        'city',
        'address',
        'postal_code',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'mobile',
        'password',
        'type',
        'status',
        'note',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'status' => UserStatus::class,
        ];
    }

    public function userMetas(): HasMany
    {
        return $this->hasMany(UserMeta::class, 'uid');
    }

    public function metaValue(string $key): ?string
    {
        return $this->userMetas->firstWhere('key', $key)?->value;
    }

    public function setMetaValues(array $values): void
    {
        foreach ($values as $key => $value) {
            if (! in_array($key, self::META_KEYS, true)) {
                continue;
            }

            $this->userMetas()->updateOrCreate(
                ['key' => $key],
                ['value' => filled($value) ? (string) $value : null],
            );
        }
    }
}
