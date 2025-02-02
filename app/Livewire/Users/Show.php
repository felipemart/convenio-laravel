<?php

declare(strict_types = 1);

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public ?User $user = null;

    public bool $modal = false;

    public function render()
    {
        return view('livewire.users.show');
    }

    #[On('user.showing')]
    public function loadUser(int $userId): void
    {
        $this->user  = User::withTrashed()->find($userId);
        $this->modal = true;
    }
}
