<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use App\Notifications\PasswordRecoveryNotification;
use Livewire\Component;

class Recovery extends Component
{
    public ?string $message = null;

    public ?string $email = null;

    public function render()
    {
        return view('livewire.auth.password.recovery')
            ->layout('components.layouts.guest', ['title' => 'Recuperar senha']);
    }

    protected function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }

    protected function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'email'    => 'O campo :attribute deve ser um e-mail válido.',
        ];
    }

    public function startPasswordRecovery()
    {

        $this->validate();

        $user = User::query()->where('email', $this->email)->first();

        $user->notify(new PasswordRecoveryNotification());

        $this->message = 'Email enviado com processo de recuperação de senha.';
    }
}
