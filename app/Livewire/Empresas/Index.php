<?php

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\{Attributes\Computed, Component, WithPagination};

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

    public function render()
    {
        return view('livewire.empresas.index');
    }
    public function updatedPerPage($value): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'razao_social', 'label' => 'Razão Social'],
            ['key' => 'nome_fantasia', 'label' => 'Nome Fantasia'],
            ['key' => 'role_id', 'label' => 'Tipo empresa'],
        ];
    }
    #[Computed]
    public function empresas(): LengthAwarePaginator
    {
        return Empresa::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(razao_social)'),
                    'like',
                    "%" . strtolower($this->search) . "%"
                )->orWhere(
                    DB::raw('lower(nome_fantasia)'),
                    'like',
                    "%" . strtolower($this->search) . "%"
                )->orWhere(
                    DB::raw('lower(cnpj)'),
                    'like',
                    "%" . strtolower($this->search) . "%"
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
                    "%" . strtolower($this->razao_social) . "%"
                )
            )
            ->when($this->search_trash, fn (Builder $q) => $q->onlyTrashed())
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }
    public function destroy(int $id): void
    {
        $this->dispatch('empresas.deletion', empresaId: $id)->to('empresas.delete');

    }

    public function restore(int $id): void
    {
        $this->dispatch('empresas.restoring', empresaId: $id)->to('empresas.restore');

    }
}
