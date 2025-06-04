<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'avatar',
        'facebook_id',
        'google_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Verificar si el usuario se registró via OAuth (Google/Facebook)
     */
    public function isOAuthUser()
    {
        return !empty($this->google_id) || !empty($this->facebook_id) || $this->provider !== 'local';
    }

    /**
     * NO usar mutator automático para password
     * Se manejará manualmente en el código
     */
    // REMOVIDO: public function setPasswordAttribute($password)
}
