<?php

declare(strict_types = 1);

namespace App\Livewire\Convenio;

use App\Models\Convenio;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Delete extends Component
{
    use Toast;

    public ?Convenio $convenio = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: [
        'required'  => 'Para deletar a empresas digite "DELETAR"',
        'confirmed' => 'Para deletar a empresas digite "DELETAR"',
    ])]
    public string $confirmDestroy = 'DELETAR';

    public ?string $confirmDestroy_confirmation = null;

    public function render()
    {
        return view('livewire.convenio.delete');
    }

    #[On('convenio.deletion')]
    public function openConfirmationfor(int $convenioId): void
    {
        $this->convenio = Convenio::find($convenioId);
        $this->modal    = true;
    }

    public function destroy(): void
    {
        $this->validate();

        if (auth()->user()->empresa_id == $this->convenio->empresa_id) {
            $this->addError('confirmDestroy', 'Não pode deletar a propria empresas');

            return;
        }
        $this->convenio->delete();
        $this->convenio->deleted_by = auth()->user()->id;
        $this->convenio->save();
        $this->success(
            'Deletado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('convenio.deleted');
        $this->reset('modal');
    }
}
