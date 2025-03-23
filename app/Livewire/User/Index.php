<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

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

    public bool $search_trash = false;

    public function mount(): void
    {
        auth()->user()->hasPermission('usuario.list') ?: $this->redirectRoute('dashboard');
    }

    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }

    #[On('user.deleted')]
    #[On('user.restored')]
    public function render(): View
    {
        return view('livewire.user.index');
    }

    #[Computed]
    public function users(): LengthAwarePaginator
    {
        $this->validate(['searchRole' => 'exists:roles,id']);

        $role    = auth()->user()->role_id;
        $empresa = auth()->user()->empresa_id;

        return User::query()
            ->with('empresa')
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(name)'),
                    'like',
                    "%" . strtolower((string) $this->search) . "%"
                )->orWhere(
                    DB::raw('lower(email)'),
                    'like',
                    "%" . strtolower((string) $this->search) . "%"
                )
            )->when(
                $this->nome,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(name)'),
                    'like',
                    "%" . strtolower((string) $this->nome) . "%"
                )
            )->when(
                $this->email,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(email)'),
                    'like',
                    "%" . strtolower((string) $this->email) . "%"
                )
            )->when(
                $this->searchRole,
                fn (Builder $query) => $query->whereIn('role_id', $this->searchRole)
            )
            ->where(function ($query) use ($role, $empresa): void {
                if ($role == 2) { // Operadora
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('convenios.empresa_id', 'users.empresa_id')
                            ->where('operadoras.empresa_id', $empresa);
                    })->orWhereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'users.empresa_id')
                            ->where('operadoras.empresa_id', $empresa);
                    })->orWhereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('operadoras')
                            ->whereColumn('operadoras.empresa_id', 'users.empresa_id')
                            ->where('operadoras.empresa_id', $empresa);
                    });
                } elseif ($role == 3) { // Convênio
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('convenios.empresa_id', 'users.empresa_id')
                            ->where('convenios.empresa_id', $empresa);
                    })->orWhereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'users.empresa_id')
                            ->where('convenios.empresa_id', $empresa);
                    });
                } elseif ($role == 4) { // Conveniada
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'users.empresa_id')
                            ->where('conveniadas.empresa_id', $empresa);
                    });
                }
            })
            ->when($this->search_trash, fn (Builder $q) => $q->onlyTrashed())
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'name', 'label' => 'Nome'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'empresa', 'label' => 'Empresa', 'sortable' => false],
            ['key' => 'roles', 'label' => 'Nivel', 'sortable' => false],
        ];
    }

    public function filterRole(?string $value = null): void
    {
        $this->roleToSearch = Role::query()->when($value, fn (Builder $q) => $q->where('name', 'like', "%$value%"))
            ->orderBy('name')
            ->get();
    }

    public function destroy(int $id): void
    {
        $this->dispatch('user.deletion', userId: $id)->to('user.delete');
    }

    public function restore(int $id): void
    {
        $this->dispatch('user.restoring', userId: $id)->to('user.restore');
    }

    public function show(int $id): void
    {
        $this->dispatch('user.showing', userId: $id)->to('user.show');
    }
}
