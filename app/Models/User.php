<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\{EmailRecuperacaoSenha};
use App\Traits\{HasPermissions, HasRoles};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasPermissions;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return string
     */
    public function getKeyPermissions(): string
    {
        return "user:{$this->id}:permissions";
    }

    /**
     * @return string
     */
    public function getKeyRole(): string
    {
        return "user:{$this->id}:roles";
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new EmailRecuperacaoSenha($token));
    }

    public function loginCachePermissions(): void
    {
        $this->cachePermissions();
    }

    public function loginCacheRoles(): void
    {
        $this->cacheRoles();
    }

    public function logoutCachePermissions(): void
    {
        $this->deleteCachePermissions();
    }

    public function logoutCacheRoles(): void
    {
        $this->deleteCacheRoles();
    }
}
