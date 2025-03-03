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
        $this->roleUser    = 2;// auth()->user()->role_id;
        $this->empresaUser = 2;// auth()->user()->empresa_id;

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
        switch ($this->roleSelect) {
            case 2:
                $this->empresas = Empresa::query()
                    ->select('empresas.id', DB::raw("CONCAT(razao_social, ' - ', nome_fantasia) AS descricao_empresa"))
                    ->join('operadoras', 'empresas.id', '=', 'operadoras.empresa_id')
                    ->where('empresas.id', '!=', $this->empresaSelect)->toArray();

                break;
            case 3:
                $this->empresas = Empresa::query()
                    ->select('empresas.id', DB::raw("CONCAT(razao_social, ' - ', nome_fantasia) AS descricao_empresa"))
                    ->join('convenios', 'empresas.id', '=', 'convenios.empresa_id')
                    ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                    ->when($this->roleUser, function ($query) {
                        if ($this->roleUser == 2) {
                            $query->where('operadoras.empresa_id', $this->empresaUser);
                        }

                        if ($this->roleUser == 3) {
                            $query->where('convenios.empresa_id', $this->empresaUser);
                        }
                    })
                    ->toArray();

                break;
            case 4:
                $this->empresas = Empresa::query()
                    ->select('empresas.id', DB::raw("CONCAT(razao_social, ' - ', nome_fantasia) AS descricao_empresa"))
                    ->join('conveniadas', 'empresas.id', '=', 'conveniadas.empresa_id')
                    ->join('convenios', 'convenio.id', '=', 'conveniadas.convenio_id')
                    ->join('operadoras', 'convenios.operadora_id', '=', 'operadoras.id')
                    ->when($this->roleUser, function ($query) {
                        if ($this->roleUser == 2) {
                            $query->where('operadoras.empresa_id', $this->empresaUser);
                        }

                        if ($this->roleUser == 3) {
                            $query->where('convenios.empresa_id', $this->empresaUser);
                        }

                        if ($this->roleUser == 4) {
                            $query->where('conveniadas.empresa_id', $this->empresaUser);
                        }
                    })
                    ->toArray();

                break;
            default:
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

            if (count($e->operadora)) {
                $this->roles = $this->removeTipo($this->roles, 2);
            }

            if (count($e->convenios)) {
                $this->roles = $this->removeTipo($this->roles, 3);
            }

            if (count($e->conveniadas)) {
                $this->roles = $this->removeTipo($this->roles, 4);
            }
        }
    }

    public function save(): void
    {
        $this->validate();
    }

    public function removeTipo($array, $id)
    {
        $novoArray = array_filter($array, function ($item) use ($id) {
            return $item['id'] !== $id;
        });

        return array_values($novoArray);
    }
}
