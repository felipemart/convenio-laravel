<?php

declare(strict_types = 1);

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasRoles
{
    public function role(): HasOne
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function giveRole(string $role): void
    {
        $keyRole       = $role;
        $role          = Role::firstOrCreate(['name' => $keyRole]);
        $this->role_id = $role->id;
        $this->save();
    }

    public function removeRole(string $role): void
    {
        $role = Role::firstOrCreate(['name' => $role]);
        $this->role()->detach($role->id);
    }

    public function hasRole(string | array $role): bool
    {
        if (is_array($role)) {
            foreach ($role as $r) {
                if ($this->hasRole($r)) {
                    return true;
                }
            }

            return false;
        }

        return auth()->user()->role()->where('name', '=', $role)->exists() ? true : false;
    }
}
