<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;

trait HasPermissions
{
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermission(string $permission): void
    {
        $this->permissions()->firstOrCreate(['permission' => $permission]);

        Cache::forget($this->getKeyPermissions());
        Cache::rememberForever($this->getKeyPermissions(), fn () => $this->permissions);
    }

    public function givePermissionId(int $idPpermission): void
    {
        $this->permissions()->firstOrCreate(['id' => $idPpermission]);

        Cache::forget($this->getKeyPermissions());
        Cache::rememberForever($this->getKeyPermissions(), fn () => $this->permissions);
    }

    public function removePermission($idPermission): void
    {
        $this->permissions()->detach($idPermission);
        Cache::forget($this->getKeyPermissions());
        Cache::rememberForever($this->getKeyPermissions(), fn () => $this->permissions);
    }

    public function hasPermission(string $permission): bool
    {
        /** @var Collection $permissions */
        $permissions = Cache::get($this->getKeyPermissions(), $this->permissions);

        return $permissions->where('permission', '=', $permission)->isNotEmpty();
    }

    public function cachePermissions(): void
    {
        Cache::forget($this->getKeyPermissions());
        Cache::rememberForever($this->getKeyPermissions(), fn () => $this->permissions);
    }

    public function deleteCachePermissions(): void
    {
        Cache::forget($this->getKeyPermissions());
    }
}
