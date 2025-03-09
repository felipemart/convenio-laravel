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
    </x-form>

    <x-button label="Cancelar" wire:navigate href="{{ route('conveniada.list')  }}"/>
    <x-button label="Salvar" wire:click="save" class="btn-primary"/>
</div>
