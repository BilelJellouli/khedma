<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'random_password' => 'boolean',
        'role' => UserRole::class,
        'deleted_at' => 'datetime',
        'banned_at' => 'datetime',
    ];

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class, 'customer_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'customer_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class, 'customer_id');
    }
}
