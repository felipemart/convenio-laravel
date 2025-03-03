<?php

declare(strict_types = 1);

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
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
        $role    = auth()->user()->role_id;
        $empresa = auth()->user()->empresa_id;

        if (! empty($this->search)) {
            $this->resetPage();
        }

        return Empresa::query()
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
            ->where(function ($query) use ($role, $empresa): void {
                if ($role == 2) { // Operadora
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('convenios.empresa_id', 'empresas.id')
                            ->where('operadoras.empresa_id', $empresa);
                    })->orWhereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'empresas.id')
                            ->where('operadoras.empresa_id', $empresa);
                    });
                } elseif ($role == 3) { // Convênio
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('convenios.empresa_id', 'empresas.id')
                            ->where('convenios.empresa_id', $empresa);
                    })->orWhereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'empresas.id')
                            ->where('convenios.empresa_id', $empresa);
                    });
                } elseif ($role == 4) { // Conveniada
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'empresas.id')
                            ->where('conveniadas.empresa_id', $empresa);
                    });
                }
            })
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
