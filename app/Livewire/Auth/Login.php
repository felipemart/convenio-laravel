<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public ?string $email = null;

    public ?string $password = null;

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function tryLogin(): void
    {

        if (!Auth::attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ])) {

            $this->addError('invalidCredentials', 'Credenciais inválidas.');

            return;
        }

        $this->redirect(route('dashboard'));
    }
}
