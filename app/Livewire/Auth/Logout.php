<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
{
    public function render()
    {
        return view('livewire.auth.logout');
    }

    public function logout(): void
    {
        auth()->user()->logoutCachePermissions();
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
    }
}
