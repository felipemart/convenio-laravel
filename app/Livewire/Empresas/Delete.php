<?php

declare(strict_types = 1);

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Delete extends Component
{
    use Toast;

    public ?Empresa $empresa = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: [
        'required'  => 'Para deletar a empresas digite "DELETAR"',
        'confirmed' => 'Para deletar a empresas digite "DELETAR"',
    ])]
    public string $confirmDestroy = 'DELETAR';

    public ?string $confirmDestroy_confirmation = null;

    public function render()
    {
        return view('livewire.empresas.delete');
    }

    #[On('empresas.deletion')]
    public function openConfirmationfor(int $empresaId): void
    {
        $this->empresa = Empresa::find($empresaId);
        $this->modal   = true;
    }

    public function destroy(): void
    {
        $this->validate();

        if (auth()->user()->empresa_id == $this->empresa->id) {
            $this->addError('confirmDestroy', 'Não pode deletar a propria empresas');

            return;
        }
        $this->empresa->delete();
        $this->empresa->deleted_by = auth()->user()->id;
        $this->empresa->save();
        $this->success(
            'Deletado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('empresas.deleted');
        $this->reset('modal');
    }
}
