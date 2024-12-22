<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
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
