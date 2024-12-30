<x-modal wire:model="modal" title="Dados do usuario: {{ $user?->name }}" class="backdrop-blur">

    <x-input label="Nome" value="{{$user?->name}}" class="mb-2" disabled/>
    <x-input label="Email" value="{{$user?->email}}" class="mb-2" disabled/>
    <x-input label="Nivel de acesso" value="{{$user?->role->name}}" class="mb-2" disabled/>
    <br/>
    <x-button label="Cancelar" @click="$wire.modal = false"/>
</x-modal>

