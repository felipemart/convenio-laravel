<?php

declare(strict_types = 1);

namespace App\Livewire\Operadora;

use App\Models\Operadora;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Operadora $operadora = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: ['required' => 'Para restaurar a empresa digite "RESTAURAR"', 'confirmed' => 'Para restaurar a empresa digite "RESTAURAR"'])]
    public string $confirmRestore = 'RESTAURAR';

    public ?string $confirmRestore_confirmation = null;

    public function render()
    {
        return view('livewire.operadora.restore');
    }

    #[On('operadora.restoring')]
    public function openConfirmationfor(int $operadoraId): void
    {
        $this->operadora = Operadora::withTrashed()->find($operadoraId);
        $this->modal     = true;
    }

    public function restore(): void
    {
        $this->validate();

        $this->operadora->restore();
        $this->operadora->restored_at = now();
        $this->operadora->restored_by = auth()->user()->id;
        $this->operadora->save();

        $this->success(
            'Restaurado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('operadora.restored');
        $this->reset('modal');
    }
}
