<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Delete extends Component
{
    public User $user;

    public bool $model = false;

    #[Rule(['required', 'confirmed'])]
    public string $confirmDestroy = 'DELETE';

    public ?string $confirmDestroy_confirmation = null;

    public function render(): View
    {
        return view('livewire.users.delete');
    }

    public function destroy(): void
    {
        $this->validate();
        $this->user->delete();
        $this->dispatch('user.deleted');
    }
}
