<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\{On, Validate};
use Livewire\Component;
use Mary\Traits\Toast;

class Delete extends Component
{
    use Toast;

    public ?User $user = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: ['required' => 'Para deletar o usuario digite "DELETAR"', 'confirmed' => 'Para deletar o usuario digite "DELETAR"'])]
    public string $confirmDestroy = 'DELETAR';

    public ?string $confirmDestroy_confirmation = null;

    public function render(): View
    {
        return view('livewire.users.delete');
    }

    #[On('user.deletion')]
    public function openConfirmationfor(int $userId): void
    {
        $this->user  = User::select('id', 'name', 'email')->find($userId);
        $this->modal = true;
    }

    public function destroy(): void
    {
        $this->validate();

        if ($this->user->is(auth()->user())) {
            $this->addError('confirmDestroy', 'Nao pode deletar o proprio usuario');

            return;
        }

        $this->user->delete();
        $this->success(
            'Deletado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('user.deleted');
        $this->reset('modal');
    }
}
