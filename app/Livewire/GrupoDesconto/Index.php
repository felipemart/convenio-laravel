<?php

declare(strict_types = 1);

namespace App\Livewire\GrupoDesconto;

use App\Models\GrupoDesconto;
use App\Models\Operadora;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public ?int $operadoraId = 0;

    public bool $search_trash = false;

    #[On('grupoDesconto.deleted')]
    #[On('grupoDesconto.restored')]
    public function render()
    {
        return view('livewire.grupo-desconto.index');
    }

    public function mount($id = 0): void
    {
        auth()->user()->hasPermission('grupoDesconto.list') ?: $this->redirectRoute('dashboard');

        if (! in_array(auth()->user()->role_id, [1, 2])) {
            $this->redirectRoute('dashboard');
        }

        if (auth()->user()->role_id == 1) {
            $this->operadoraId = intval($id);
        } else {
            $this->operadoraId = Operadora::where('empresa_id', '=', auth()->user()->empresa_id)->first()->id;
        }
    }

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'id', 'label' => 'id', 'class' => 'w-16'],
            ['key' => 'descricao', 'label' => 'Descrição'],
        ];
    }

    #[Computed]
    public function grupoDesconto(): LengthAwarePaginator
    {
        if ($this->search !== null && $this->search !== '' && $this->search !== '0') {
            $this->resetPage();
        }

        return GrupoDesconto::select()
            ->paginate($this->perPage);
    }

    public function destroy(int $id): void
    {
        $this->dispatch('grupoDesconto.deletion', convenioId: $id)->to('grupoDesconto.delete');
    }

    public function restore(int $id): void
    {
        $this->dispatch('grupoDesconto.restoring', convenioId: $id)->to('grupoDesconto.restore');
    }
}
