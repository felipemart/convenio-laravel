<?php

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Livewire\Attributes\{On, Validate};
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?Empresa $empresa = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: ['required' => 'Para restaurar a empresa digite "RESTAURAR"', 'confirmed' => 'Para restaurar a empresa digite "RESTAURAR"'])]
    public string $confirmRestore = 'RESTAURAR';

    public ?string $confirmRestore_confirmation = null;

    public function render()
    {
        return view('livewire.empresas.restore');
    }
    #[On('empresas.restoring')]
    public function openConfirmationfor(int $empresaId): void
    {
        $this->empresa = Empresa::select('nome_fantasia')->withTrashed()->find($empresaId);
        $this->modal   = true;
    }

    public function restore(): void
    {
        //        $this->validate();
        //
        //        if ($this->empresa->is(auth()->empresa())) {
        //            $this->addError('confirmDestroy', 'Nao pode deletar o proprio usuario');
        //
        //            return;
        //        }
        //
        //        $this->empresa->restore();
        //        $this->empresa->restored_at = now();
        //        $this->empresa->restored_by = auth()->empresa()->id;
        //        $this->empresa->save();
        //
        //        $this->success(
        //            'Restaurado com sucesso!',
        //            null,
        //            'toast-top toast-end',
        //            'o-information-circle',
        //            'alert-info',
        //            3000
        //        );
        //        $this->dispatch('empresa.restored');
        //        $this->reset('modal');
    }
}
