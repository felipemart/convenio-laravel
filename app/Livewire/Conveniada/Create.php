<?php

declare(strict_types = 1);

namespace App\Livewire\Conveniada;

use App\Actions\CnpjBuscaDados;
use App\Models\Convenio;
use App\Models\Empresa;
use Exception;
use Illuminate\Contracts\View\View;
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

    public ?int $convenioId = 0;

    public function mount($id = 0): void
    {
        if (! in_array(auth()->user()->role_id, [1, 2, 3])) {
            $this->redirectRoute('dashboard');
        }

        if (in_array(auth()->user()->role_id, [1, 2])) {
            $this->convenioId = intval($id);
        } else {
            $this->convenioId = Convenio::where('empresa_id', '=', auth()->user()->empresa_id)->first()->id;
        }
    }

    public function render(): View
    {
        return view('livewire.conveniada.create');
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
        $cnpjBuscaDados = new CnpjBuscaDados();
        $dados          = $cnpjBuscaDados->execute($this->cnpj);

        $this->razao_social  = $dados['razao_social'];
        $this->nome_fantasia = $dados['nome_fantasia'];
        $this->logradouro    = $dados['logradouro'];
        $this->bairro        = $dados['bairro'];
        $this->cep           = $dados['cep'];
        $this->uf            = $dados['uf'];
        $this->cidade        = $dados['cidade'];
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

            $empresa->giveConveniada(Convenio::where('id', $this->convenioId)->first()->empresa_id);
            $this->success(
                'Usuário criado com sucesso!',
                null,
                'toast-top toast-end',
                'o-information-circle',
                'alert-info',
                3000
            );

            $this->redirect(route('conveniada.list', ['id' => $this->convenioId]));
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
