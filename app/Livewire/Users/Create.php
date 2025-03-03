<?php

declare(strict_types = 1);

namespace App\Livewire\Users;

use App\Models\Empresa;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Notifications\BemVindoNotification;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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
        $role = auth()->user()->role_id;

        $this->updateSetPermissions();
        $this->roles = Role::query()
            ->where('id', '>=', $role)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.users.create');
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
                    "%" . strtolower((string) $this->search) . "%"
                )
            )
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);
    }

    public function updateSetPermissions(): void
    {
        if ($this->user instanceof User) {
            $this->user->permissions()->each(function ($permission): void {
                $this->setPermissions[$permission->id] = true;
            });
        }
    }

    public function changeEmpresa(): void
    {
        $role          = $this->roleSelect;
        $empresa       = auth()->user()->empresa_id;
        $this->empresa = [];

        if ($this->roleSelect !== null && $this->roleSelect !== 0) {
            $this->empresa = Empresa::where(function ($query) use ($role, $empresa): void {
                if ($role == 2) { // Operadora
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('convenios.empresa_id', 'empresas.id')
                            ->where('operadoras.empresa_id', $empresa);
                    })->orWhereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'empresas.id')
                            ->where('operadoras.empresa_id', $empresa);
                    });
                } elseif ($role == 3) { // Convênio
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('convenios.empresa_id', 'empresas.id')
                            ->where('convenios.empresa_id', $empresa);
                    })->orWhereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'empresas.id')
                            ->where('convenios.empresa_id', $empresa);
                    });
                } elseif ($role == 4) { // Conveniada
                    $query->whereExists(function ($q) use ($empresa): void {
                        $q->select(DB::raw(1))
                            ->from('conveniadas')
                            ->join('convenios', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'empresas.id')
                            ->where('conveniadas.empresa_id', $empresa);
                    });
                }
            })
                ->get()->toArray();
        }
    }

    public function updatePermissions($idPermisson): void
    {
        if ($this->user instanceof User) {
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

    public function next(): ?bool
    {
        if (! $this->saveOnly && $this->step == 1 && ! $this->save()) {
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

        return null;
    }

    public function save(): bool
    {
        $this->validate();
        $this->user = User::create([
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => bcrypt(str()->random(10)),
            'empresa_id' => $this->empresaSelect,

        ]);

        $this->user->notify(new BemVindoNotification());
        $this->user->sendEmailVerificationNotification();

        if ($this->user->name) {
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

        return false;
    }
}
