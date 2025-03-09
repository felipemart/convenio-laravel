<?php

declare(strict_types = 1);

namespace App\Livewire\Conveniada;

use App\Models\Conveniada;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Delete extends Component
{
    use Toast;

    public ?Conveniada $conveniada = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: [
        'required'  => 'Para deletar a empresas digite "DELETAR"',
        'confirmed' => 'Para deletar a empresas digite "DELETAR"',
    ])]
    public string $confirmDestroy = 'DELETAR';

    public ?string $confirmDestroy_confirmation = null;

    public function render()
    {
        return view('livewire.conveniada.delete');
    }

    #[On('conveniada.deletion')]
    public function openConfirmationfor(int $conveniadaId): void
    {
        $this->conveniada = Conveniada::find($conveniadaId);
        $this->modal      = true;
    }

    public function destroy(): void
    {
        $this->validate();

        if (auth()->user()->empresa_id == $this->conveniada->empresa_id) {
            $this->addError('confirmDestroy', 'Não pode deletar a propria empresas');

            return;
        }
        $this->conveniada->delete();
        $this->conveniada->deleted_by = auth()->user()->id;
        $this->conveniada->save();
        $this->success(
            'Deletado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('conveniada.deleted');
        $this->reset('modal');
    }
}
