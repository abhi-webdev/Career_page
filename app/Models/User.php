<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isHR(): bool
    {
        return $this->role === 'hr';
    }

    public function isTR(): bool
    {
        return $this->role === 'tr';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isCandidate(): bool
    {
        return $this->role === 'user';
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relationships
    |--------------------------------------------------------------------------
    */

    public function resumes(): HasMany
    {
        return $this->hasMany(
            Resume::class,
            'user_id'
        );
    }

    public function applications(): HasMany
    {
        return $this->hasMany(
            Application::class,
            'user_id'
        );
    }

    public function scheduledInterviews(): HasMany
    {
        return $this->hasMany(
            Interview::class,
            'scheduled_by'
        );
    }

    public function offers(): HasMany
    {
        return $this->hasMany(
            Offer::class,
            'offered_by'
        );
    }

    public function employee(): HasOne
    {
        return $this->hasOne(
            Employee::class,
            'user_id'
        );
    }

    public function roleChangesMade(): HasMany
    {
        return $this->hasMany(
            RoleChangeLog::class,
            'changed_by'
        );
    }
}