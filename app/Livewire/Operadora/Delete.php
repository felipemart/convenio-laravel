<?php

declare(strict_types = 1);

namespace App\Livewire\Operadora;

use App\Models\Operadora;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Delete extends Component
{
    use Toast;

    public ?Operadora $operadora = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: [
        'required'  => 'Para deletar a empresas digite "DELETAR"',
        'confirmed' => 'Para deletar a empresas digite "DELETAR"',
    ])]
    public string $confirmDestroy = 'DELETAR';

    public ?string $confirmDestroy_confirmation = null;

    public function render(): View
    {
        return view('livewire.operadora.delete');
    }

    #[On('operadora.deletion')]
    public function openConfirmationfor(int $operadoraId): void
    {
        $this->operadora = Operadora::find($operadoraId);
        $this->modal     = true;
    }

    public function destroy(): void
    {
        $this->validate();

        if (auth()->user()->empresa_id == $this->operadora->empresa_id) {
            $this->addError('confirmDestroy', 'Não pode deletar a propria empresas');

            return;
        }

        $this->operadora->delete();
        $this->operadora->deleted_by = auth()->user()->id;
        $this->operadora->save();
        $this->success(
            'Deletado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('operadora.deleted');
        $this->reset('modal');
    }
}
