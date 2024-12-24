<?php

namespace App\Livewire\Users;

use App\Models\{Role, User};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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

    public Collection $roleToSearch;

    public ?array $searchRole = [];

    public function mount(): void
    {
        $this->filterRole();

    }

    public function render(): View
    {
        return view('livewire.users.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate(['searchRole' => 'exists:roles,id']);

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
                $this->searchRole,
                fn (Builder $q) => $q->whereHas(
                    'roles',
                    fn (Builder $query) => $query->whereIn('id', $this->searchRole)
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

    public function filterRole(?string $value = null): void
    {
        $this->roleToSearch = Role::query()->when($value, fn (Builder $q) => $q->where('role', 'like', "%$value%"))
            ->orderBy('role')
            ->get();

    }
}
