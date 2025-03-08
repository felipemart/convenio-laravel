<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasRoles
{
    private function getKeySessionRole(): string
    {
        return "user:" . $this->id . ".roles";
    }

    public function role(): BelongsTo
    {
        return $this->BelongsTo(Role::class, 'role_id', 'id');
    }

    public function giveRole(string $role): void
    {
        $role = Role::firstOrCreate(['name' => ucfirst($role)]);
        $this->role()->associate($role);

        $this->makeSessionRoles();
        $this->save();
    }

    public function revokeRole(string $key): void
    {
        $this->role()->where('key', '=', $key)->delete();
        $this->makeSessionRoles();
    }

    public function hasRole(string | array $key): bool
    {
        if (is_array($key)) {
            foreach ($key as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }

            return false;
        }

        $k = $this->getKeySessionRole();

        if (! session()->has($k)) {
            $this->makeSessionRoles();
        }
        /** @var Collection<int, Role> */
        $role = session()->get($k);

        return  $role->where('name', '=', ucfirst($key))->isNotEmpty();
    }

    public function makeSessionRoles(): void
    {
        $k = $this->getKeySessionRole();
        session([$k => $this->role()->get()]);
    }
}
