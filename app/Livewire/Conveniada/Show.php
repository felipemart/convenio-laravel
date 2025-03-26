<?php

declare(strict_types = 1);

namespace App\Livewire\Conveniada;

use App\Models\Conveniada;
use Livewire\Component;

class Show extends Component
{
    public ?Conveniada $conveniada = null;

    public ?int  $convenioId = 0;

    public string $selectedTab = 'users-tab';

    public function mount(int $id): void
    {
        $this->conveniada = Conveniada::withTrashed()->find($id);
        $this->convenioId = $this->conveniada->convenio_id;
    }

    public function render()
    {
        return view('livewire.conveniada.show');
    }
}
