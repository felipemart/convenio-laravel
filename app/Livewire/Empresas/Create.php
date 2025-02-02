<?php

declare(strict_types = 1);

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Create extends Component
{
    public array $roles = [];

    public array $empresa = [];

    public ?int $roleSelect = null;

    public ?int $empresaSelect = null;

    public array $empresas = [];

    public int $roleUser = 2; //auth()->user()->role_id;

    public int $empresaUser = 2; //auth()->user()->role_id;

    public function render()
    {
        return view('livewire.empresas.create');
    }

    public function mount(): void
    {
        $this->roles = Role::query()
            ->where('id', '>', $this->roleUser)
            ->orderBy('name')
            ->get()->toArray();
    }

    public function changeRoles()
    {
        $this->empresas = Empresa::query()
            ->when(
                $this->roleUser == 2,
                fn (Builder $q) => $q->where('operadora_id', '=', $this->empresaUser)
            )
            ->when(
                $this->roleUser == 3,
                fn (Builder $q) => $q->where('convenio_id', '=', $this->empresaUser)
            )
            ->when(
                $this->roleUser == 4,
                fn (Builder $q) => $q->where('conveniada_id', '=', $this->empresaUser)
            )
            ->where('role_id', ($this->roleSelect - 1))
            ->get()->toArray();
    }
}
