<div>

    <x-header title="Dados da Empresa: {{$nome_fantasia}}" separator progress-indicator>

    </x-header>

    <x-card>
        <x-tabs wire:model="selectedTab">
            <x-tab name="users-tab" label="Informações" icon="o-users">
                <x-form wire:submit="save">
                    <x-input label="CNPJ" wire:model="cnpj" :value="$convenio->empresa->cnpj" class=""/>
                    <x-input label="Nome Fantasia" wire:model="nome_fantasia"
                             :value="$convenio->empresa->nome_fantasia"/>
                    <x-input label="Razao Social" wire:model="razao_social" :value="$convenio->empresa->razao_social"
                             class=""/>
                    <x-input label="Logradouro" wire:model="logradouro" :value="$convenio->empresa->logradouro"
                             class=""/>
                    <x-input label="Bairro" wire:model="bairro" :value="$convenio->empresa->bairro" class=""/>
                    <x-input label="CEP" wire:model="cep" :value="$convenio->empresa->cep" class=""/>
                    <x-input label="UF" wire:model="uf" :value="$convenio->empresa->uf" class=""/>
                    <x-input label="Cidade" wire:model="cidade" :value="$convenio->empresa->cidade" class=""/>
                    <x-input label="Email" wire:model="email" :value="$convenio->empresa->email" class=""/>


                    <br/>
                </x-form>

                <x-button label="Voltars" wire:navigate
                          href="{{ route('convenio.list',  ['id' => $this->operadoraId])  }}"/>
                <x-button label="Salvar" wire:click="save" class="btn-primary"/>
            </x-tab>

            <x-tab name="tricks-tab" label="Configuração" icon="o-adjustments-horizontal">

                <div>convenio</div>
            </x-tab>

        </x-tabs>
    </x-card>
</div>
