<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function mount(): void
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
    }
}
