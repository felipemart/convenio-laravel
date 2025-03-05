<?php

declare(strict_types = 1);

namespace App\Livewire\Empresas;

use App\Models\Empresa;
use Livewire\Component;

class Update extends Component
{
    public ?int $roleUser = null;

    public ?Empresa $empresa = null;

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

    public string $selectedTab = 'users-tab';

    public function mount(int $id): void
    {
        $this->empresa = Empresa::find($id);

        $this->cnpj          = $this->empresa->cnpj;
        $this->nome_fantasia = $this->empresa->nome_fantasia;
        $this->razao_social  = $this->empresa->razao_social;
        $this->logradouro    = $this->empresa->logradouro;
        $this->bairro        = $this->empresa->bairro;
        $this->cep           = $this->empresa->cep;
        $this->uf            = $this->empresa->uf;
        $this->cidade        = $this->empresa->cidade;
        $this->email         = $this->empresa->email;
        $this->roleUser      = auth()->user()->role_id;
        $this->empresaUser   = auth()->user()->empresa_id;
    }

    public function render()
    {
        return view('livewire.empresas.update');
    }
}
