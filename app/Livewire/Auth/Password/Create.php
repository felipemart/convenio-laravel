<?php

declare(strict_types = 1);

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Create extends Component
{
    public ?string $token = null;

    public ?string $email = null;

    public ?string $password = null;

    public ?string $password_confirmation = null;

    public function mount(?string $token = null, ?string $email = null): void
    {
        $this->token = request('token', $token);
        $this->email = request('email', $email);

        if ($this->tokenInvalido()) {
            session()->flash('status', 'Token inválido.');
            $this->redirectRoute('login');
        }
    }

    protected function rules(): array
    {
        return [
            'password' => 'required|confirmed|min:8',
            'email'    => 'required|email',
        ];
    }

    protected function messages(): array
    {
        return [
            'required'  => 'O campo :attribute é obrigatório.',
            'min'       => 'O campo :attribute deve ter no mínimo :min caracteres.',
            'confirmed' => 'As senhas não conferem.',
            'email'     => 'O campo :attribute deve ser um e-mail válido.',
        ];
    }

    public function render(): View
    {
        return view('livewire.auth.password.create')
            ->layout('components.layouts.guest', ['title' => 'Criação de senha']);
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

    public function criarSenha(): void
    {
        $this->validate();

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password): void {
                $user->password       = $password;
                $user->remember_token = Str::random(60);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status != Password::PASSWORD_RESET) {
            session()->flash('status', 'Ocorreu um erro ao criar a senha.');

            if ($status == Password::INVALID_USER) {
                session()->flash('status', 'Não conseguimos encontrar um usuário com esse endereço de e-mail.');
            }

            return;
        }

        session()->flash('status', 'Senha criada com sucesso.');
        $this->redirect(route('login'));
    }

    #[Computed]
    public function obfuscarEmail(): string
    {
        return obfuscarEmail($this->email);
    }
}
