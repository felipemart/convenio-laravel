<?php

declare(strict_types = 1);

namespace App\Livewire\Auth\Password;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class Recovery extends Component
{
    public ?string $message = null;

    public ?string $email = null;

    public function render()
    {
        return view('livewire.auth.password.recuperacao-senha')
            ->layout('components.layouts.guest', ['title' => 'Recuperar senha']);
    }

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'email'    => 'O campo :attribute deve ser um e-mail válido.',
        ];
    }

    public function recuperacaoSenha(): void
    {
        $this->validate();
        Password::sendResetLink($this->only('email'));

        $this->message = 'Email enviado com processo de recuperação de senha.';
    }
}
