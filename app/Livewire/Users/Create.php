<?php

namespace App\Livewire\Users;

use App\Models\{Empresa, Permission, Role, User};
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\{Builder, Collection};
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public ?User $user = null;

    public Collection $roles;

    public array $empresa = [];

    public ?int $roleSelect = null;

    public ?int $empresaSelect = null;

    public ?int $id = null;

    public int $perPage = 10;

    public array $sortBy = ['column' => 'id', 'direction' => 'asc'];

    public ?string $search = null;

    public array $setPermissions = [];

    public string $name = '';

    public string $email = '';

    public int $step = 1;

    public bool $saveOnly = false;

    public function mount(): void
    {
        $this->updateSetPermissions();
        $this->roles = Role::query()
            ->orderBy('name')
            ->get();

    }
    public function render()
    {
        return view('livewire.users.create');
    }

    protected function rules()
    {
        return [
            'name'       => 'required',
            'email'      => 'required|email|unique:users,email,' . $this->id,
            'roleSelect' => 'required |exists:roles,id',
        ];
    }

    protected function messages()
    {
        return [
            'roleSelect.required' => 'O campo Nivel de acesso é obrigatório.',
            'required'            => 'O campo :attribute é obrigatório.',
            'email'               => 'O campo :attribute deve ser um e-mail válido.',
            'unique'              => 'O campo :attribute já existe.',

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
                    "%" . strtolower($this->search) . "%"
                )
            )
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function updateSetPermissions(): void
    {
        if ($this->user) {
            $this->user->permissions()->each(function ($permission) {
                $this->setPermissions[$permission->id] = true;
            });
        }

    }

    public function changeEmpresa(): void
    {
        $this->empresa = [];

        if (!empty($this->roleSelect)) {
            $this->empresa = Empresa::where('role_id', $this->roleSelect)->get()->toArray();
        }

    }

    public function updatePermissions($idPermisson): void
    {

        if ($this->user) {
            if ($this->setPermissions[$idPermisson]) {
                $this->user->givePermissionId($idPermisson);
            } else {
                $this->user->removePermission($idPermisson);
            }
        } else {
            $this->setPermissions = [];
            $this->error(
                'Erro, deve cadastrar o usuário antes!',
                null,
                'toast-top toast-end',
                'o-exclamation-triangle',
                'alert-info',
                3000
            );
        }

    }

    public function next()
    {
        if (!$this->saveOnly && $this->step == 1 && !$this->save()) {
            return false;
        }

        if ($this->step == 2) {
            $this->success(
                'Salvo com sucesso!',
                null,
                'toast-top toast-end',
                'o-information-circle',
                'alert-info',
                3000
            );
            $this->redirect(route('user.list'));
        }

        $this->saveOnly = true;
        $this->step     = 2;
    }

    public function save()
    {
        $this->validate();
        $this->user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => bcrypt('123456'),
            'role_id'  => $this->roleSelect,

        ]);

        if ($this->user) {
            $this->success(
                'Salvo com sucesso!',
                null,
                'toast-top toast-end',
                'o-information-circle',
                'alert-info',
                3000
            );

            return;
        }
        $this->error(
            'Erro ao salvar!',
            null,
            'toast-top toast-end',
            'o-exclamation-triangle',
            'alert-info',
            3000
        );

    }
}
