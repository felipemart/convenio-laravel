<div>

    <x-header title="Criação da Empresa:" separator progress-indicator>

    </x-header>
    <x-form wire:submit="save">
        <x-input label="CNPJ" wire:model="cnpj" wire:change="cnpjCarregaDados" class="mb-2"/>
        <x-input label="Nome Fantasia" wire:model="nome_fantasia"/>
        <x-input label="Razao Social" wire:model="razao_social" class="mb-2"/>
        <x-input label="Logradouro" wire:model="logradouro" class="mb-2"/>
        <x-input label="Bairro" wire:model="bairro" class="mb-2"/>
        <x-input label="CEP" wire:model="cep" class="mb-2"/>
        <x-input label="UF" wire:model="uf" class="mb-2"/>
        <x-input label="Cidade" wire:model="cidade" class="mb-2"/>
        <x-input label="Email" wire:model="email" class="mb-2"/>

        <x-select
            label="Tipo da empresa"
            :options="$roles"
            option-value="id"
            option-label="name"
            placeholder="Selecionar um nivel"
            placeholder-value="0"
            wire:change="changeRoles"
            wire:model="roleSelect"/>

        @if($roleSelect > 3 || ($roleUser == 1 && $roleSelect == 3))
            <x-select
                label="Selectionar empresa"
                :options="$empresas"
                option-value="id"
                option-label="descricao_empresa"
                placeholder="Selecionar um nivel"
                placeholder-value="0"
                wire:model="empresaSelect"/>
        @endif
        <br/>
    </x-form>

    <x-button label="Cancelar" @click="$wire.modal = false"/>
    <x-button label="Salvar" wire:click="save" class="btn-primary"/>
</div>
