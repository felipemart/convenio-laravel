<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class PermissionUser extends Component
{
    use Toast;
    use WithPagination;

    public User $user;

    public ?int $roleSelect = null;

    public int $perPage = 10;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public ?string $search = null;

    public array $setPermissions = [];

    public function render()
    {
        return view('livewire.user.permission');
    }

    public function mount(int $id): void
    {
        $this->user = User::withTrashed()->find($id);
        $this->updateSetPermissions();
        $this->roleSelect = $this->user->role_id;
    }

    #[Computed]
    public function headers(): array
    {
        return [
            [
                'key'   => 'descricao',
                'label' => 'Permissão',
            ],
        ];
    }

    #[Computed]
    public function permissions(): LengthAwarePaginator
    {
        return Permission::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(descricao)'),
                    'like',
                    '%' . strtolower((string) $this->search) . '%'
                )
            )
            ->where('permissions.role_id', '>=', $this->user->role_id)
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function updateSetPermissions(): void
    {
        $this->user->permissions()->each(function ($permission): void {
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
