<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
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

    public int $perPage = 10;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public ?string $search = null;

    public array $setPermissions = [];

    public string $name = '';

    public string $email = '';

    public function mount(int $id): void
    {
        $this->user = User::withTrashed()->find($id);
        $this->updateSetPermissions();
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

    #[Computed]
    public function headers(): array
    {
        return [
            ['key' => 'permission', 'label' => 'Permissão'],
        ];
    }

    #[Computed]
    public function permissions(): LengthAwarePaginator
    {
        return Permission::query()
            ->when(
                $this->search,
                fn (Builder $q) => $q->where(
                    DB::raw('lower(permission)'),
                    'like',
                    "%" . strtolower((string) $this->search) . "%"
                )
            )
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function updateSetPermissions(): void
    {
        $this->user->permissions()->each(function ($permission): void {
            $this->setPermissions[$permission->id] = true;
        });
    }

    public function updatePermissions($idPermisson): void
    {
        if ($this->setPermissions[$idPermisson]) {
            $this->user->givePermissionId($idPermisson);
        } else {
            $this->user->removePermission($idPermisson);
        }
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
