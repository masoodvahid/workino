<?php

namespace App\Models;

use App\Enums\UserRoleKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    public const SPACE_USER_PERMISSION_OPTIONS = [
        'dashboard.view' => 'داشبورد',
        'spaces.view_any' => 'منوی مراکز',
        'spaces.view' => 'نمایش مرکز',
        'spaces.create' => 'ایجاد مرکز',
        'spaces.update' => 'ویرایش مرکز',
        'spaces.delete' => 'حذف مرکز',
        'bookings.menu' => 'منوی رزرو (آینده)',
        'payments.menu' => 'منوی پرداخت (آینده)',
    ];

    protected $fillable = [
        'key',
        'title',
        'permissions',
    ];

    protected function casts(): array
    {
        return [
            'key' => UserRoleKey::class,
            'permissions' => 'array',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions ?? [], true);
    }
}
