<?php

namespace App\Livewire\Users;

use App\Models\{Permission, Role, User};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DataUseser extends Component
{
    public User $user;

    public Collection $roles;

    public ?string $roleSelect = null;

    public string $selectedTab = 'users-tab';

    public int $perPage = 10;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public ?string $search = null;

    public array $setPermissions = [];

    public function mount(?int $id)
    {
        $this->user       = User::find($id);
        $this->roles      = Role::query()->get();
        $roletmp          = $this->user->roles->first();
        $this->roleSelect = $roletmp ? $roletmp->id : null;
        $this->updateSetPermissions();

    }

    public function render()
    {
        return view('livewire.users.data-useser');
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'permission', 'label' => 'Permissão'],
        ];
    }
    #[Computed]
    public function permissions(): LengthAwarePaginator
    {
        return Permission::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(permission)'),
                    'like',
                    "%" . strtolower($this->search) . "%"
                )
            )
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function updateSetPermissions(): void
    {

        $this->user->permissions()->each(function ($permission) {
            $this->setPermissions[$permission->id] = true;
        });

    }

    public function updatePermissions($idPermisson): void
    {

        if ($this->setPermissions[$idPermisson]) {
            $this->user->givePermissionId($idPermisson);
        } else {
            $this->user->removePermission($idPermisson);
        }

    }

}
