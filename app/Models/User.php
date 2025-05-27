<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method string|null checkStatus()
 * @method \Laravel\Sanctum\NewAccessToken createToken(string $name, array $abilities = [])
 * @method bool isAdmin()
 * @method bool isUser()
 */
#[ObservedBy(UserObserver::class)]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'is_admin',
        'username',
    ];

    protected $dates = ['deleted_at'];

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
     * Check the user's status for login validation.
     *
     * @return string|null Returns error message if status invalid, null if valid.
     */
    public function checkStatus(): ?string
    {
        if (! $this->email_verified_at) {
            return 'Your account is not verified';
        }

        if (! $this->is_active) {
            return 'Your account is not active';
        }

        if ($this->is_suspended) {
            return 'Your account is suspended';
        }

        return null;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_suspended' => 'boolean',
        'is_admin' => 'boolean',
    ];

    /**
     * Determine if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin == 1;
    }

    /**
     * Determine if the user is a user/client.
     */
    public function isUser(): bool
    {
        return $this->is_admin == 0;
    }

    /**
     * Get the profile associated with this user.
     *
     * @return HasOne<Profile>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
}
