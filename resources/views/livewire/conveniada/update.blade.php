<div>

    <x-header title="Dados da Empresa: {{$nome_fantasia}}" separator progress-indicator>

    </x-header>
    <x-tabs wire:model="selectedTab">
        <x-tab name="users-tab" label="Informações" icon="o-users">
            <x-form wire:submit="save">
                <x-input label="CNPJ" wire:model="cnpj" :value="$conveniada->empresa->cnpj" class="mb-2"/>
                <x-input label="Nome Fantasia" wire:model="nome_fantasia" :value="$conveniada->empresa->nome_fantasia"/>
                <x-input label="Razao Social" wire:model="razao_social" class="mb-2"
                         :value="$conveniada->empresa->razao_social"/>
                <x-input label="Logradouro" wire:model="logradouro" class="mb-2"
                         :value="$conveniada->empresa->logradouro"/>
                <x-input label="Bairro" wire:model="bairro" class="mb-2" :value="$conveniada->empresa->bairro"/>
                <x-input label="CEP" wire:model="cep" class="mb-2" :value="$conveniada->empresa->cep"/>
                <x-input label="UF" wire:model="uf" class="mb-2" :value="$conveniada->empresa->uf"/>
                <x-input label="Cidade" wire:model="cidade" class="mb-2" :value="$conveniada->empresa->cidade"/>
                <x-input label="Email" wire:model="email" class="mb-2" :value="$conveniada->empresa->email"/>


                <br/>
            </x-form>

            <x-button label="Cancelar" wire:navigate href="{{ route('conveniada.list')  }}"/>
            <x-button label="Salvar" wire:click="save" class="btn-primary"/>
        </x-tab>

        <x-tab name="tricks-tab" label="Configuração" icon="o-adjustments-horizontal">

            <div>conveniada</div>
        </x-tab>

    </x-tabs>
</div>
