<?php

declare(strict_types = 1);

namespace App\Livewire\Conveniada;

use App\Models\Conveniada;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Conveniada $conveniada = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: ['required' => 'Para restaurar a empresa digite "RESTAURAR"', 'confirmed' => 'Para restaurar a empresa digite "RESTAURAR"'])]
    public string $confirmRestore = 'RESTAURAR';

    public ?string $confirmRestore_confirmation = null;

    public function render()
    {
        return view('livewire.conveniada.restore');
    }

    #[On('conveniada.restoring')]
    public function openConfirmationfor(int $conveniadaId): void
    {
        $this->conveniada = Conveniada::withTrashed()->find($conveniadaId);
        $this->modal      = true;
    }

    public function restore(): void
    {
        $this->validate();

        $this->conveniada->restore();
        $this->conveniada->restored_at = now();
        $this->conveniada->restored_by = auth()->user()->id;
        $this->conveniada->save();

        $this->success(
            'Restaurado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('conveniada.restored');
        $this->reset('modal');
    }
}
