<?php

namespace App\Livewire\Auth\Password;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Hash};
use Livewire\Component;

class ResetSenha extends Component
{
    public ?string $token = null;

    public function mount(): void
    {
        $this->token = request('token');

        if ($this->tokenInvalido()) {
            session()->flash('status', 'Token inválido.');
            $this->redirectRoute('login');
        }

    }
    public function render(): View
    {
        return view('livewire.auth.password.reset-senha')
            ->layout('components.layouts.guest', ['title' => 'Recuperação de senha']);
    }
    private function tokenInvalido(): bool
    {
        $tokens = DB::table('password_reset_tokens')->get(['token']);

        foreach ($tokens as $t) {
            if (Hash::check($this->token, $t->token)) {
                return false;
            }
        }

        return true;

    }
}
