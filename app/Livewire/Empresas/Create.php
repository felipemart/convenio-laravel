<?php

declare(strict_types = 1);

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public array $roles = [];

    public array $empresa = [];

    public ?int $roleSelect = null;

    public ?int $empresaSelect = null;

    public array $empresas = [];

    public ?int $roleUser = null;

    public ?int $empresaUser = null;

    public string $cnpj = '';

    public string $nome_fantasia = '';

    public string $razao_social = '';

    public string $logradouro = '';

    public string $bairro = '';

    public string $cep = '';

    public string $uf = '';

    public string $cidade = '';

    public string $email = '';

    public function render()
    {
        return view('livewire.empresas.create');
    }

    public function mount(): void
    {
        $this->roleUser    = auth()->user()->role_id;
        $this->empresaUser = auth()->user()->empresa_id;

        $this->roles = Role::query()
            ->where('id', '>', $this->roleUser)
            ->orderBy('name')
            ->get()->toArray();
    }

    protected function rules(): array
    {
        return [
            'cnpj'          => 'required|min:14|max:14',
            'razao_social'  => 'required',
            'nome_fantasia' => 'required',
            'logradouro'    => 'required',
            'bairro'        => 'required',
            'cep'           => 'required',
            'uf'            => 'required',
            'cidade'        => 'required',
            'email'         => 'required',
            'roleSelect'    => 'required|exists:roles,id',

        ];
    }

    protected function messages(): array
    {
        return [
            'roleSelect.required' => 'O campo tipo empresa é obrigatório.',
            'required'            => 'O campo :attribute é obrigatório.',
            'email'               => 'O campo :attribute deve ser um e-mail válido.',
            'unique'              => 'O campo :attribute já existe.',

        ];
    }

    public function changeRoles(): void
    {
        if ($this->roleSelect !== null && $this->roleSelect !== 0) {
            $role          = $this->roleSelect;
            $empresa       = auth()->user()->empresa_id;
            $roleUser      = auth()->user()->role_id;
            $this->empresa = Empresa::select('empresas.id', DB::raw("CONCAT(razao_social, ' - ', nome_fantasia) AS descricao_empresa"))
                ->where(function ($query) use ($role, $empresa, $roleUser): void {
                    if ($role == 2) { // Operadora
                        $query->whereExists(function ($q) use ($roleUser): void {
                            if ($roleUser == 1) {
                                $q->select(DB::raw(1))->whereExists(function ($q): void {
                                    $q->select(DB::raw(1))
                                        ->from('operadoras')
                                        ->whereColumn('operadoras.empresa_id', 'empresas.id');
                                });
                            }
                        });
                    } elseif ($role == 3) { // Convênio
                        $query->whereExists(function ($q) use ($roleUser): void {
                            if ($roleUser == 1) {
                                $q->select(DB::raw(1))
                                    ->whereExists(function ($q): void {
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
                                ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                                ->whereColumn('convenios.empresa_id', 'empresas.id')
                                ->where(function ($query) use ($empresa): void {
                                    $query->where('convenios.empresa_id', $empresa)
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

    public function cnpjCarregaDados(): void
    {
        $e = Empresa::where('cnpj', $this->cnpj)->first();

        if (! empty($e)) {
            $this->razao_social  = $e->razao_social;
            $this->nome_fantasia = $e->nome_fantasia;
            $this->logradouro    = $e->logradouro;
            $this->bairro        = $e->bairro;
            $this->cep           = $e->cep;
            $this->uf            = $e->uf;
            $this->cidade        = $e->cidade;
            $this->email         = $e->email;

            if ($e->operadora()->exists()) {
                $this->roles = $this->removeTipo($this->roles, 2);
            }

            if ($e->convenios()->exists()) {
                $this->roles = $this->removeTipo($this->roles, 3);
            }

            if ($e->conveniadas()->exists()) {
                $this->roles = $this->removeTipo($this->roles, 4);
            }
        }
    }

    public function save(): void
    {
        $this->validate();

        $empresa = Empresa::firstOrCreate([
            'cnpj'          => $this->cnpj,
            'razao_social'  => $this->razao_social,
            'nome_fantasia' => $this->nome_fantasia,
            'logradouro'    => $this->logradouro,
            'bairro'        => $this->bairro,
            'cep'           => $this->cep,
            'uf'            => $this->uf,
            'cidade'        => $this->cidade,
            'email'         => $this->email,
        ]);

        $id_pai = $this->empresaSelect !== null && $this->empresaSelect !== 0 ? $this->empresaSelect : auth()->user()->empresa_id;

        switch ($this->roleSelect) {
            case 2:
                $empresa->giveOperadora();

                break;
            case 3:
                $empresa->giveConvenio($id_pai);

                break;
            case 4:
                $empresa->giveConveniada($id_pai);

                break;
            default:
                break;
        }
    }

    public function removeTipo($array, $id): array
    {
        $novoArray = array_filter($array, fn (array $item): bool => $item['id'] !== $id);

        return array_values($novoArray);
    }
}
