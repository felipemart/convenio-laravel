<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Mary\Traits\Toast;

class Update extends Component
{
    use Toast;

    public User $user;

    public Collection $roles;

    public ?int $roleSelect = null;

    public ?int $id = null;

    public string $selectedTab = 'users-tab';

    public ?string $search = null;

    public string $name = '';

    public string $email = '';

    public function mount(int $id): void
    {
        $this->user  = User::withTrashed()->find($id);
        $this->roles = Role::query()
            ->orderBy('name')
            ->get();
        $this->roleSelect = $this->user->role_id;
        $this->name       = $this->user->name;
        $this->email      = $this->user->email;
    }

    public function render()
    {
        return view('livewire.user.update');
    }

    protected function rules(): array
    {
        return [
            'name'       => 'required',
            'email'      => 'required|email|unique:users,email,' . $this->id,
            'roleSelect' => 'required |exists:roles,id',
        ];
    }

    protected function messages(): array
    {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'email'    => 'O campo :attribute deve ser um e-mail válido.',
        ];
    }

    public function save(): ?bool
    {
        $this->validate();
        $this->user->name    = $this->name;
        $this->user->email   = $this->email;
        $this->user->role_id = $this->roleSelect;

        if ($this->user->save()) {
            $this->success(
                'Salvo com sucesso!',
                null,
                'toast-top toast-end',
                'o-information-circle',
                'alert-info',
                3000
            );

            return true;
        }
        $this->error(
            'Erro ao salvar!',
            null,
            'toast-top toast-end',
            'o-exclamation-triangle',
            'alert-info',
            3000
        );

        return null;
    }
}
