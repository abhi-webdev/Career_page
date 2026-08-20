<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

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
}