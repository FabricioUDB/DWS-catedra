<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'facebook_id',
        'avatar',
        'provider',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    /**
     * Verificar si el usuario se registró via OAuth
     */
    public function isOAuthUser(): bool
    {
        return !empty($this->google_id) || !empty($this->facebook_id);
    }

    /**
     * Obtener el proveedor de autenticación
     */
    public function getAuthProvider(): string
    {
        if ($this->google_id) return 'google';
        if ($this->facebook_id) return 'facebook';
        return 'email';
    }

    /**
     * Verificar si tiene password (usuarios de email)
     */
    public function hasPassword(): bool
    {
        return !empty($this->password);
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para usuarios OAuth
     */
    public function scopeOAuth($query)
    {
        return $query->where(function($q) {
            $q->whereNotNull('google_id')
              ->orWhereNotNull('facebook_id');
        });
    }

    /**
     * Scope para usuarios por proveedor
     */
    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }
}
