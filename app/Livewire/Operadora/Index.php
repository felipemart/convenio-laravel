<?php

declare(strict_types = 1);

namespace App\Livewire\Operadora;

use App\Models\Operadora;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public bool $filtros = false;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public ?string $search = null;

    public ?string $razao_social = null;

    public ?string $cnpj = null;

    public bool $search_trash = false;

    #[On('operadora.deleted')]
    #[On('operadora.restored')]
    public function render()
    {
        return view('livewire.operadora.index');
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'razao_social', 'label' => 'Razão Social'],
            ['key' => 'nome_fantasia', 'label' => 'Nome Fantasia'],
        ];
    }

    #[Computed]
    public function operadoras(): LengthAwarePaginator
    {
        $role    = auth()->user()->role_id;
        $empresa = auth()->user()->empresa_id;

        if ($this->search !== null && $this->search !== '' && $this->search !== '0') {
            $this->resetPage();
        }

        return Operadora::select(['operadoras.*', 'empresas.razao_social', 'empresas.nome_fantasia'])
            ->join('empresas', 'operadoras.empresa_id', '=', 'empresas.id')
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(razao_social)'),
                    'like',
                    "%" . strtolower((string) $this->search) . "%"
                )->orWhere(
                    DB::raw('lower(nome_fantasia)'),
                    'like',
                    "%" . strtolower((string) $this->search) . "%"
                )->orWhere(
                    DB::raw('lower(cnpj)'),
                    'like',
                    "%" . strtolower((string) $this->search) . "%"
                )
            )->when(
                $this->cnpj,
                fn (Builder $q) => $q->where(
                    DB::raw('(cnpj)'),
                    'like',
                    "%" . ($this->cnpj) . "%"
                )
            )->when(
                $this->razao_social,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(razao_social)'),
                    'like',
                    "%" . strtolower((string) $this->razao_social) . "%"
                )
            )
            ->when($this->search_trash, fn (Builder $q) => $q->onlyTrashed())
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function destroy(int $id): void
    {
        $this->dispatch('operadora.deletion', operadoraId: $id)->to('operadora.delete');
    }

    public function restore(int $id): void
    {
        $this->dispatch('operadora.restoring', operadoraId: $id)->to('operadora.restore');
    }
}
