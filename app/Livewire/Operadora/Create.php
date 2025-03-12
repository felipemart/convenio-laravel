<?php

declare(strict_types = 1);

namespace App\Livewire\Operadora;

use App\Models\Empresa;
use Exception;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

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
        return view('livewire.operadora.create');
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

    public function cnpjCarregaDados(): void
    {
        $response = Http::get("https://publica.cnpj.ws/cnpj/{$this->cnpj}");

        if ($response->status() == 200) {
            $e = json_decode($response->body());

            $this->razao_social  = $e->razao_social;
            $this->nome_fantasia = $e->estabelecimento->nome_fantasia ?: '';
            $this->logradouro    = $e->estabelecimento->logradouro;
            $this->bairro        = $e->estabelecimento->bairro;
            $this->cep           = $e->estabelecimento->cep;
            $this->uf            = $e->estabelecimento->estado->sigla;
            $this->cidade        = $e->estabelecimento->cidade->nome;
        }
    }

    public function save(): void
    {
        $this->validate();

        try {
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

            $empresa->giveOperadora();
            $this->success(
                'Usuário criado com sucesso!',
                null,
                'toast-top toast-end',
                'o-information-circle',
                'alert-info',
                3000
            );

            $this->redirect(route('operadora.list'));
        } catch (Exception) {
            $this->error(
                'Erro ao criar o usuário!',
                null,
                'toast-top toast-end',
                'o-exclamation-triangle',
                'alert-info',
                3000
            );
        }
    }
}
