<?php

declare(strict_types = 1);

namespace App\Livewire\Convenio;

use App\Models\Convenio;
use Livewire\Component;

class Show extends Component
{
    public ?Convenio $convenio = null;

    public ?int  $operadoraId = 0;

    public string $selectedTab = 'users-tab';

    public function mount(int $id): void
    {
        $this->convenio    = Convenio::withTrashed()->find($id);
        $this->operadoraId = $this->convenio->operadora_id;
    }

    public function render()
    {
        return view('livewire.convenio.show');
    }
}
