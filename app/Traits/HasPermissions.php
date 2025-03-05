<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Cache;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

trait HasPermissions
{
    private function getKeySession(): string
    {
        return "user:" . $this->id . ".permissions";
    }

    /** @return BelongsToMany<Permission, $this> */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermission(string $key): void
    {
        $this->permissions()->firstOrCreate(['permission' => $key]);
        $this->makeSessionPermissions();
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

    /**
     * @param string|array<string> $key
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function hasPermission(string | array $key): bool
    {
        if (is_array($key)) {
            foreach ($key as $k) {
                if ($this->hasPermission($k)) {
                    return true;
                }
            }

            return false;
        }

        $k = $this->getKeySession();

        if (! session()->has($k)) {
            $this->makeSessionPermissions();
        }
        /** @var Collection<int, Permission> */
        $permissons = session()->get($k);

        return  $permissons->where('permission', '=', $key)->isNotEmpty();
    }

    public function revokePermission(string $key): void
    {
        $this->permissions()->where('key', '=', $key)->delete();
        $this->makeSessionPermissions();
    }

    public function makeSessionPermissions(): void
    {
        $k = $this->getKeySession();
        session([$k => $this->permissions]);
    }
}
