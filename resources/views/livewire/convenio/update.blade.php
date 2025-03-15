<div>

    <x-header title="Dados da Empresa: {{$nome_fantasia}}" separator progress-indicator>

    </x-header>
    <x-tabs wire:model="selectedTab">
        <x-tab name="users-tab" label="Informações" icon="o-users">
            <x-form wire:submit="save">
                <x-input label="CNPJ" wire:model="cnpj" :value="$convenio->empresa->cnpj" class="mb-2"/>
                <x-input label="Nome Fantasia" wire:model="nome_fantasia" :value="$convenio->empresa->nome_fantasia"/>
                <x-input label="Razao Social" wire:model="razao_social" :value="$convenio->empresa->razao_social"
                         class="mb-2"/>
                <x-input label="Logradouro" wire:model="logradouro" :value="$convenio->empresa->logradouro"
                         class="mb-2"/>
                <x-input label="Bairro" wire:model="bairro" :value="$convenio->empresa->bairro" class="mb-2"/>
                <x-input label="CEP" wire:model="cep" :value="$convenio->empresa->cep" class="mb-2"/>
                <x-input label="UF" wire:model="uf" :value="$convenio->empresa->uf" class="mb-2"/>
                <x-input label="Cidade" wire:model="cidade" :value="$convenio->empresa->cidade" class="mb-2"/>
                <x-input label="Email" wire:model="email" :value="$convenio->empresa->email" class="mb-2"/>


                <br/>
            </x-form>

            <x-button label="Cancelar" wire:navigate href="{{ route('convenio.list')  }}"/>
            <x-button label="Salvar" wire:click="save" class="btn-primary"/>
        </x-tab>

        <x-tab name="tricks-tab" label="Configuração" icon="o-adjustments-horizontal">

            <div>convenio</div>
        </x-tab>

    </x-tabs>
</div>
