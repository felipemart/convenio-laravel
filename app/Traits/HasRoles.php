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

    public function hasRole(RoleEnum|string|array $role): bool
    {
        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }

            return false;
        }

        $keyRole = $role instanceof RoleEnum ? $role->value : $role;
        /** @var Collection $roles */
        $roles = Cache::get($this->getKeyRole(), $this->roles);

        return $roles->where('role', '=', $keyRole)->isNotEmpty();
    }

    public function cacheRoles()
    {
        Cache::forget($this->getKeyRole());
        Cache::rememberForever($this->getKeyRole(), fn () => $this->roles);
    }

    public function deleteCacheRoles()
    {
        Cache::forget($this->getKeyRole());
    }

}
