<?php

declare(strict_types = 1);

namespace App\Livewire\User;

use App\Models\Empresa;
use App\Models\Role;
use App\Models\User;
use App\Notifications\BemVindoNotification;
use App\Notifications\EmailCriacaoSenha;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
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

        $this->roles = Role::query()
            ->where('id', '>=', $role)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('livewire.user.create');
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

    public function changeEmpresa(): void
    {
        $role          = $this->roleSelect;
        $empresa       = auth()->user()->empresa_id;
        $roleUser      = auth()->user()->role_id;
        $this->empresa = [];

        if ($this->roleSelect !== null && $this->roleSelect !== 0) {
            $this->empresa = Empresa::where(function ($query) use ($role, $empresa, $roleUser): void {
                if ($role == 2) { // Operadora
                    $query->whereExists(function ($q) use ($empresa, $roleUser): void {
                        $r = $q->select(DB::raw(1))
                            ->from('operadoras')
                            ->whereColumn('operadoras.empresa_id', 'empresas.id')
                            ->where(function ($query) use ($empresa): void {
                                $query->where('operadoras.empresa_id', $empresa);
                            });

                        if ($roleUser == 1) {
                            $r->orWhereExists(function ($q): void {
                                $q->select(DB::raw(1))
                                    ->from('operadoras')
                                    ->whereColumn('operadoras.empresa_id', 'empresas.id');
                            });
                        }
                    });
                } elseif ($role == 3) { // Convênio
                    $query->whereExists(function ($q) use ($empresa, $roleUser): void {
                        $r = $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('convenios.empresa_id', 'empresas.id')
                            ->where(function ($query) use ($empresa): void {
                                $query->Where('convenios.empresa_id', $empresa)
                                    ->orWhere('operadoras.empresa_id', $empresa);
                            });

                        if ($roleUser == 1) {
                            $r->orWhereExists(function ($q): void {
                                $q->select(DB::raw(1))
                                    ->from('convenios')
                                    ->whereColumn('convenios.empresa_id', 'empresas.id');
                            });
                        }
                    });
                } elseif ($role == 4) { // Conveniada
                    $query->whereExists(function ($q) use ($empresa, $roleUser): void {
                        $r = $q->select(DB::raw(1))
                            ->from('convenios')
                            ->join('conveniadas', 'conveniadas.convenio_id', '=', 'convenios.id')
                            ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                            ->whereColumn('conveniadas.empresa_id', 'empresas.id')
                            ->where(function ($query) use ($empresa): void {
                                $query->Where('conveniadas.empresa_id', $empresa)
                                    ->orWhere('convenios.empresa_id', $empresa)
                                    ->orWhere('operadoras.empresa_id', $empresa);
                            });

                        if ($roleUser == 1) {
                            $r->orWhereExists(function ($q): void {
                                $q->select(DB::raw(1))
                                    ->from('conveniadas')
                                    ->whereColumn('conveniadas.empresa_id', 'empresas.id');
                            });
                        }
                    });
                }
            })
                ->get()->toArray();
        }
    }

    public function save(): bool
    {
        $this->validate();
        $this->user = User::create([
            'name'       => $this->name,
            'email'      => $this->email,
            'password'   => bcrypt(str()->random(10)),
            'empresa_id' => $this->empresaSelect,
            'role_id'    => $this->roleSelect,

        ]);

        $this->user->notify(new BemVindoNotification());
        $token = Password::createToken($this->user);
        $this->user->notify(new EmailCriacaoSenha($token));

        if ($this->user->name) {
            $this->success(
                'Usuário criado com sucesso!',
                null,
                'toast-top toast-end',
                'o-information-circle',
                'alert-info',
                3000,
                route('user.list')
            );

            return true;
        }
        $this->error(
            'Erro ao criar o usuário!',
            null,
            'toast-top toast-end',
            'o-exclamation-triangle',
            'alert-info',
            3000
        );

        return false;
    }
}
