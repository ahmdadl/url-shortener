<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

final class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * @return Scope<Builder<User>, Builder<User>>
     */
    #[Scope]
    public function admin(Builder $query): Builder
    {
        return $query->where('role', UserRole::ADMIN);
    }

    /**
     * @return Scope<Builder<User>, Builder<User>>
     */
    #[Scope]
    public function user(Builder $query): Builder
    {
        return $query->where('role', UserRole::USER);
    }

    /**
     * @return Scope<Builder<User>, Builder<User>>
     */
    #[Scope]
    public function guest(Builder $query): Builder
    {
        return $query->where('role', UserRole::GUEST);
    }

    /**
     * @return Attribute<bool, void>
     */
    protected function isAdmin(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $_, array $attributes): bool => $attributes['role'] === UserRole::ADMIN->value,
        )->shouldCache();
    }

    /**
     * @return Attribute<bool, void>
     */
    protected function isUser(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $_, array $attributes): bool => $attributes['role'] === UserRole::USER->value,
        )->shouldCache();
    }

    /**
     * @return Attribute<bool, void>
     */
    protected function isGuest(): Attribute
    {
        return Attribute::make(
            get: static fn (mixed $_, array $attributes): bool => $attributes['role'] === UserRole::GUEST->value,
        )->shouldCache();
    }
}
