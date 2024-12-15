<x-form wire:submit="registrarUsuario">
    <x-input label="Nome" wire:model="name"/>
    <x-input label="Email" wire:model="email"/>
    <x-input label="Confirmar email" wire:model="email_confirmation"/>
    <x-input label="Senha" wire:model="password" type="password"/>

    <x-slot:actions>
        <x-button label="Cancelar"/>
        <x-button label="Salvar" class="btn-primary" type="submit" spinner="save"/>
    </x-slot:actions>
</x-form>
