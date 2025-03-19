<?php

declare(strict_types = 1);

namespace App\Livewire\Conveniada;

use App\Models\Conveniada;
use App\Models\Convenio;
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

    public ?int $convenioId = 0;

    public bool $search_trash = false;

    #[On('conveniada.deleted')]
    #[On('conveniada.restored')]
    public function render()
    {
        return view('livewire.conveniada.index');
    }

    public function mount($id = 0): void
    {
        auth()->user()->hasPermission('conveniada.list') ?: $this->redirectRoute('dashboard');

        if (! in_array(auth()->user()->role_id, [1, 2, 3])) {
            $this->redirectRoute('dashboard');
        }

        if (in_array(auth()->user()->role_id, [1, 2])) {
            $this->convenioId = intval($id);
        } else {
            $this->convenioId = Convenio::where('empresa_id', '=', auth()->user()->empresa_id)->first()->id;
        }
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
    public function conveniadas(): LengthAwarePaginator
    {
        if ($this->search !== null && $this->search !== '' && $this->search !== '0') {
            $this->resetPage();
        }

        return Conveniada::select(['conveniadas.*', 'empresas.razao_social', 'empresas.nome_fantasia'])
            ->join('empresas', 'conveniadas.empresa_id', '=', 'empresas.id')
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
            ->where('convenio_id', $this->convenioId)
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function destroy(int $id): void
    {
        $this->dispatch('conveniada.deletion', conveniadaId: $id)->to('conveniada.delete');
    }

    public function restore(int $id): void
    {
        //teste
        $this->dispatch('conveniada.restoring', conveniadaId: $id)->to('conveniada.restore');
    }
}
