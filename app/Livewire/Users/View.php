<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Mary\Traits\Toast;

class View extends Component
{
    use Toast;

    public ?User $user = null;

    public bool $modal = false;

    public function render()
    {
        return view('livewire.users.view');
    }

    #[On('user.viewing')]
    public function openConfirmationfor(int $userId): void
    {
        $this->user  = User::withTrashed()->find($userId);
        $this->modal = true;
    }
}
