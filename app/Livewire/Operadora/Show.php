<?php

declare(strict_types = 1);

namespace App\Livewire\Operadora;

use App\Models\Operadora;
use Livewire\Component;

class Show extends Component
{
    public ?Operadora $operadora = null;

    public string $selectedTab = 'users-tab';

    public function mount(int $id): void
    {
        $this->operadora = Operadora::withTrashed()->find($id);
    }

    public function render()
    {
        return view('livewire.operadora.show');
    }
}
