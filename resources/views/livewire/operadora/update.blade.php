<div>

    <x-header title="Dados da Empresa: {{$nome_fantasia}}" separator progress-indicator>

    </x-header>
    <x-tabs wire:model="selectedTab">
        <x-tab name="users-tab" label="Informações" icon="o-users">
            <x-form wire:submit="save">
                <x-input label="CNPJ" wire:model="cnpj" class=""/>
                <x-input label="Nome Fantasia" wire:model="nome_fantasia"/>
                <x-input label="Razao Social" wire:model="razao_social" class=""/>
                <x-input label="Logradouro" wire:model="logradouro" class=""/>
                <x-input label="Bairro" wire:model="bairro" class=""/>
                <x-input label="CEP" wire:model="cep" class=""/>
                <x-input label="UF" wire:model="uf" class=""/>
                <x-input label="Cidade" wire:model="cidade" class=""/>
                <x-input label="Email" wire:model="email" class=""/>


                <br/>
            </x-form>

            <x-button label="Cancelar" wire:navigate href="{{ route('operadora.list')  }}"/>
            <x-button label="Salvar" wire:click="save" class="btn-primary"/>
        </x-tab>

        <x-tab name="tricks-tab" label="Configuração" icon="o-adjustments-horizontal">

            <div>Operadora</div>
        </x-tab>

    </x-tabs>
</div>
