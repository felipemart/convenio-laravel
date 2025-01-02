<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\{EmailRecuperacaoSenha};
use App\Traits\{HasPermissions, HasRoles};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasPermissions;
    use SoftDeletes;
    use AuditableTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'restored_at',
        'restored_by',
        'deleted_by',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function restoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');

    }
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');

    }
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

    public function logoutCachePermissions(): void
    {
        $this->deleteCachePermissions();
    }

}
