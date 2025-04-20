<x-modal wire:model="modal" title="Dados do usuario" class="backdrop-blur-sm">

    @if($user)
        <x-input readonly label="Nome" :value="$user->name" class=""/>
        <x-input readonly label="Email" :value="$user->email"/>
        <x-input readonly label="Nivel de acesso" :value="$user->role->name" class=""/>
        <x-input readonly label="Data de criação" :value="$user->created_at->format('d/m/Y H:i')" class=""/>
        <x-input readonly label="Data da ultima atualização" :value="$user->updated_at->format('d/m/Y H:i')"
                 class=""/>
        @if($user?->deleted_at)
            <x-input readonly label="Data de exclusão" :value="$user->deleted_at?->format('d/m/Y H:i')" class=""/>
            <x-input readonly label="Deletado por" :value="$user->deletedBy?->name" class=""/>
        @endif
    @endif
    <br/>
    <x-button label="Cancelar" @click="$wire.modal = false"/>
</x-modal>

