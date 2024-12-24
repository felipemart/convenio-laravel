<?php

namespace App\Livewire\Users;

use App\Models\{Role, User};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};

/**
 * @property-read LengthAwarePaginator | User[] $users
 */
class Index extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public bool $filtros = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public ?string $search = null;

    public ?string $nome = null;

    public ?string $email = null;

    public ?array $search_role = [];

    public function render(): View
    {
        return view('livewire.users.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate(['search_role' => 'exists:roles,id']);

        return User::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(name)'),
                    'like',
                    "%" . strtolower($this->search) . "%"
                )->orWhere(
                    DB::raw('lower(email)'),
                    'like',
                    "%" . strtolower($this->search) . "%"
                )
            )->when(
                $this->nome,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(name)'),
                    'like',
                    "%" . strtolower($this->nome) . "%"
                )
            )->when(
                $this->email,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(name)'),
                    'like',
                    "%" . strtolower($this->email) . "%"
                )
            )->when(
                $this->search_role,
                fn (Builder $q) => $q->whereRaw(
                    '(select count(*)
                    from role_user
                    where role_user.user_id = users.id
                    and role_user.role_id in (?)) > 0',
                    $this->search_role
                )
            )
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => '#'],
            ['key' => 'name', 'label' => 'Name'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'roles', 'label' => 'Nivel'],
        ];
    }

    #[Computed]
    public function getAllRoles()
    {
        return Role::query()->get()->map(function (Role $role) {
            return ['id' => $role->id, 'name' => $role->role];
        });

    }
}
