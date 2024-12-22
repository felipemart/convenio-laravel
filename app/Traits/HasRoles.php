<?php

namespace App\Traits;

use App\Enum\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function giveRole(RoleEnum|string $role): void
    {
        $keyRole = $role instanceof RoleEnum ? $role->value : $role;

        $this->roles()->firstOrCreate(['role' => $keyRole]);

        Cache::forget($this->getKeyRole());
        Cache::rememberForever($this->getKeyRole(), fn () => $this->roles);
    }

    public function hasRole(RoleEnum|string $role): bool
    {
        $keyRole = $role instanceof RoleEnum ? $role->value : $role;
        /** @var Collection $roles */
        $roles = Cache::get($this->getKeyRole(), $this->roles);

        return $roles->where('role', '=', $keyRole)->isNotEmpty();
    }
}
