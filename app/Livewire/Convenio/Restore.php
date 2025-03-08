<?php

declare(strict_types = 1);

namespace App\Livewire\Convenio;

use App\Models\Convenio;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Convenio $convenio = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: ['required' => 'Para restaurar a empresa digite "RESTAURAR"', 'confirmed' => 'Para restaurar a empresa digite "RESTAURAR"'])]
    public string $confirmRestore = 'RESTAURAR';

    public ?string $confirmRestore_confirmation = null;

    public function render()
    {
        return view('livewire.convenio.restore');
    }

    #[On('convenio.restoring')]
    public function openConfirmationfor(int $convenioId): void
    {
        $this->convenio = Convenio::withTrashed()->find($convenioId);
        $this->modal    = true;
    }

    public function restore(): void
    {
        $this->validate();

        $this->convenio->restore();
        $this->convenio->restored_at = now();
        $this->convenio->restored_by = auth()->user()->id;
        $this->convenio->save();

        $this->success(
            'Restaurado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('convenio.restored');
        $this->reset('modal');
    }
}
