<?php

declare(strict_types = 1);

namespace App\Models;

use App\Notifications\EmailRecuperacaoSenha;
use App\Notifications\VerifyEmail;
use App\Traits\HasPermissions;
use App\Traits\HasRoles;
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
        'empresa_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function restoredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getKeyPermissions(): string
    {
        return "user:{$this->id}:permissions";
    }

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

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }
}
