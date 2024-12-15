<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\{Auth, RateLimiter};
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public ?string $email = null;

    public ?string $password = null;

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.guest', ['title' => 'Login']);
    }

    protected function rules()
    {
        return [
            'email'    => 'required|email',
            'password' => 'required',
        ];
    }

    protected function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'email'    => 'O campo :attribute deve ser um e-mail válido.',
        ];
    }

    public function lgoin(): void
    {

        $this->validate();

        if ($this->virificaRateLimiter()) {
            return;
        };

        if (!Auth::attempt([
            'email'    => $this->email,
            'password' => $this->password,
        ])) {

            RateLimiter::hit($this->keyLimiter());

            $this->addError('crendenciaisInvalidas', 'Credenciais inválidas.');

            return;
        }

        $this->redirect(route('dashboard'));
    }

    /**
     * @return string
     */
    private function keyLimiter(): string
    {
        return base64_encode(Str::transliterate(Str::lower($this->email)) . ':' . request()->ip());
    }

    /**
     * @return bool
     */
    private function virificaRateLimiter(): bool
    {
        if (RateLimiter::tooManyAttempts($this->keyLimiter(), 5)) {

            $this->addError('rateLimiter', 'Ultrapassou o limite de tentativas. Tente novamente em ' . RateLimiter::availableIn($this->keyLimiter()) . ' segundos.');

            return true;
        };

        return false;
    }
}
