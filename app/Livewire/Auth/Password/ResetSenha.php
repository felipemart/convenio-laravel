<?php

namespace App\Livewire\Auth\Password;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\{DB, Hash, Password};
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ResetSenha extends Component
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
    protected function rules()
    {
        return [
            'password' => 'required|confirmed|min:8',
            'email'    => 'required|email',
        ];
    }

    protected function messages()
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

    public function resetarSenha(): void
    {

        $this->validate();

        $status = Password::reset(
            $this->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password       = $password;
                $user->remember_token = Str::random(60);
                $user->save();

                event(new PasswordReset($user));
            }
        );

        session()->flash('status', $status);

        $this->redirect(route('dashboard'));

    }

    #[Computed]
    public function obfuscarEmail(): string
    {
        return obfuscar_email($this->email);

    }
}
