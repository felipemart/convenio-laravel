<?php

declare(strict_types = 1);

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Livewire\Component;

class Show extends Component
{
    public ?Empresa $empresa = null;

    public string $selectedTab = 'users-tab';

    public function mount(int $id): void
    {
        $this->empresa = Empresa::withTrashed()->find($id);
    }

    public function render()
    {
        return view('livewire.empresas.show');
    }
}
