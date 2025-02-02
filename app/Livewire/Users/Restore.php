<?php

declare(strict_types = 1);

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Mary\Traits\Toast;

class Restore extends Component
{
    use Toast;

    public ?User $user = null;

    public bool $modal = false;

    #[Validate(['required', 'confirmed'], message: ['required' => 'Para restaurar o usuario digite "RESTAURAR"', 'confirmed' => 'Para restaurar o usuario digite "RESTAURAR"'])]
    public string $confirmRestore = 'RESTAURAR';

    public ?string $confirmRestore_confirmation = null;

    public function render(): View
    {
        return view('livewire.users.restore');
    }

    #[On('user.restoring')]
    public function openConfirmationfor(int $userId): void
    {
        $this->user  = User::select('id', 'name', 'email')->withTrashed()->find($userId);
        $this->modal = true;
    }

    public function restore(): void
    {
        $this->validate();

        if ($this->user->is(auth()->user())) {
            $this->addError('confirmDestroy', 'Nao pode deletar o proprio usuario');

            return;
        }

        $this->user->restore();
        $this->user->restored_at = now();
        $this->user->restored_by = auth()->user()->id;
        $this->user->save();

        $this->success(
            'Restaurado com sucesso!',
            null,
            'toast-top toast-end',
            'o-information-circle',
            'alert-info',
            3000
        );
        $this->dispatch('user.restored');
        $this->reset('modal');
    }
}
