<div>

    <x-header title="Criação da Empresa:" separator progress-indicator>

    </x-header>
    <x-form wire:submit="save">
        <x-input label="CNPJ" wire:model="cnpj" wire:change="cnpjCarregaDados" class=""/>
        <x-input label="Nome Fantasia" wire:model="nome_fantasia"/>
        <x-input label="Razao Social" wire:model="razao_social" class=""/>
        <x-input label="Logradouro" wire:model="logradouro" class=""/>
        <x-input label="Bairro" wire:model="bairro" class=""/>
        <x-input label="CEP" wire:model="cep" class=""/>
        <x-input label="UF" wire:model="uf" class=""/>
        <x-input label="Cidade" wire:model="cidade" class=""/>
        <x-input label="Email" wire:model="email" class=""/>
    </x-form>

    <br/>
    <x-button label="Cancelar" wire:navigate href="{{ route('conveniada.list', ['id'  =>  $this->convenioId])  }}"/>
    <x-button label="Salvar" wire:click="save" class="btn-primary"/>
</div>
