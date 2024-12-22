<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\{EmailRecuperacaoSenha};
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasFactory;
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

    /**
     * @return string
     */
    public function getKeyPermissions(): string
    {
        return "user::{$this->id}::permissions";
    }

    /**
     * @return string
     */
    public function getKeyRole(): string
    {
        return "user::{$this->id}::roles";
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new EmailRecuperacaoSenha($token));
    }

    public function givePermission(string $permission): void
    {
        $this->permissions()->firstOrCreate(['permission' => $permission]);

        Cache::forget($this->getKeyPermissions());
        Cache::rememberForever($this->getKeyPermissions(), fn () => $this->permissions);
    }

    public function hasPermission(string $permission): bool
    {

        /** @var Collection $permissions */
        $permissions = Cache::get($this->getKeyPermissions(), $this->permissions);

        return $permissions->where('permission', '=', $permission)->isNotEmpty();

    }

    public function giveRole(string $role): void
    {
        $this->roles()->firstOrCreate(['role' => $role]);

        Cache::forget($this->getKeyRole());
        Cache::rememberForever($this->getKeyRole(), fn () => $this->roles);
    }

    public function hasRole(string $role): bool
    {
        /** @var Collection $roles */
        $roles = Cache::get($this->getKeyRole(), $this->roles);

        return $roles->where('role', '=', $role)->isNotEmpty();
    }

}
