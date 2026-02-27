<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRoleKey;
use App\Enums\UserStatus;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
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
        'role_id',
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function spaceUsers(): HasMany
    {
        return $this->hasMany(SpaceUser::class, 'user_id');
    }

    public function spaces(): BelongsToMany
    {
        return $this->belongsToMany(Space::class, 'space_user', 'user_id', 'space_id')
            ->using(SpaceUser::class)
            ->withPivot(['id', 'status'])
            ->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id');
    }

    public function roleKey(): string
    {
        return $this->role?->key?->value ?? UserRoleKey::User->value;
    }

    public function isAdmin(): bool
    {
        return $this->roleKey() === UserRoleKey::Admin->value;
    }

    public function isSpaceUser(): bool
    {
        return $this->roleKey() === UserRoleKey::SpaceUser->value;
    }

    public function hasPanelPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (! $this->isSpaceUser()) {
            return false;
        }

        return $this->role?->hasPermission($permission) ?? false;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->roleKey(), [UserRoleKey::Admin->value, UserRoleKey::SpaceUser->value], true);
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
