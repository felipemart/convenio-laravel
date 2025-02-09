<?php

declare(strict_types = 1);

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
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
        $this->empresas = Empresa::query()
            ->select('id', DB::raw("CONCAT(razao_social, ' - ', nome_fantasia) AS descricao_empresa"))
            ->when(
                $this->roleUser == 2,
                fn (Builder $q) => $q->where('operadora_id', '=', $this->empresaUser)
            )
            ->when(
                $this->roleUser == 3,
                fn (Builder $q) => $q->where('convenio_id', '=', $this->empresaUser)
            )
            ->when(
                $this->roleUser == 4,
                fn (Builder $q) => $q->where('conveniada_id', '=', $this->empresaUser)
            )
            ->where('role_id', ($this->roleSelect - 1))
            ->orderBy('razao_social', 'ASC')
            ->orderBy('nome_fantasia', 'ASC')
            ->get()->toArray();
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
        }
    }

    public function save(): void
    {
        $this->validate();
    }
}
