<?php

declare(strict_types = 1);

namespace App\Livewire\Auth;

use App\Models\User;
use App\Notifications\BemVindoNotification;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule(['required', 'max:255'])]
    public ?string $name = null;

    #[Rule(['required', 'email', 'max:255', 'confirmed', 'unique:users,email'])]
    public ?string $email = null;

    public ?string $email_confirmation = null;

    #[Rule(['required'])]
    public ?string $password = null;

    public function render()
    {
        return view('livewire.auth.registro');
    }

    public function registrarUsuario(): void
    {
        $this->validate();

        $user = User::query()->create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => $this->password,

        ]);

        $user->notify(new BemVindoNotification());
    }
}
