<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['permission', 'role_id'];

    public function users(): BelongsToMany
    {
        return  $this->belongsToMany(User::class);
    }

    public function roles(): BelongsToMany
    {
        return  $this->belongsToMany(Role::class);
    }
}
