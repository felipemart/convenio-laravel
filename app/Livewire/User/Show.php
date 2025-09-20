<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class Show extends Component
{
    public ?User $user = null;

    public bool $modal = false;

    public function render(): \Illuminate\Contracts\View\View | \Illuminate\Contracts\View\Factory
    {
        return view('livewire.user.show');
    }

    #[On('user.showing')]
    public function loadUser(int $userId): void
    {
        $this->user  = User::withTrashed()->find($userId);
        $this->modal = true;
    }
}
