<?php

declare(strict_types = 1);

namespace App\Livewire\Convenio;

use App\Models\Convenio;
use App\Models\Empresa;
use Exception;
use Livewire\Component;
use Mary\Traits\Toast;
use Throwable;

class Update extends Component
{
    use Toast;

    public string $selectedTab = 'users-tab';

    public ?Convenio $convenio = null;

    public string $cnpj = '';

    public string $nome_fantasia = '';

    public string $razao_social = '';

    public string $logradouro = '';

    public string $bairro = '';

    public string $cep = '';

    public string $uf = '';

    public string $cidade = '';

    public string $email = '';

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

    public function mount(int $id): void
    {
        $this->convenio = Convenio::find($id);
        /** @var Empresa $empresa */
        $empresa = $this->convenio->empresa;

        $this->cnpj          = $empresa->cnpj;
        $this->nome_fantasia = $empresa->nome_fantasia;
        $this->razao_social  = $empresa->razao_social;
        $this->logradouro    = $empresa->logradouro;
        $this->bairro        = $empresa->bairro;
        $this->cep           = $empresa->cep;
        $this->uf            = $empresa->uf;
        $this->cidade        = $empresa->cidade;
        $this->email         = $empresa->email;
    }

    public function render()
    {
        return view('livewire.convenio.update');
    }

    public function save()
    {
        $this->validate();

        try {
            /** @var Empresa $empresa */
            $empresa                = $this->convenio->empresa;
            $empresa->cnpj          = $this->cnpj;
            $empresa->razao_social  = $this->razao_social;
            $empresa->nome_fantasia = $this->nome_fantasia;
            $empresa->logradouro    = $this->logradouro;
            $empresa->bairro        = $this->bairro;
            $empresa->cep           = $this->cep;
            $empresa->uf            = $this->uf;
            $empresa->cidade        = $this->cidade;
            $empresa->email         = $this->email;

            if (! $empresa->save()) {
                throw new Exception('Erro ao atualizar a empresa da operadora!');
            }

            $this->success(
                'Empresa atualizada com sucesso!',
                null,
                'toast-top toast-end',
                'o-information-circle',
                'alert-info',
                3000
            );
        } catch (Throwable $e) {
            $this->error(
                'Erro ao atualizar a empresa!',
                null,
                'toast-top toast-end',
                'o-exclamation-triangle',
                'alert-info',
                3000
            );
        }
    }
}
