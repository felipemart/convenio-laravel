<x-modal wire:model="modal" title="Deletar {{ $empresa?->nome_fantasia }}?" class="backdrop-blur-sm">

    @error('confirmDestroy')
    <x-alert icon="o-exclamation-triangle" class="alert-warning">
        <span>{{ $message }}</span>
    </x-alert>
    @enderror

    <x-input label="Para deletar o usuario digite 'DELETAR' " value="" wire:model="confirmDestroy_confirmation"/>
    <br/>
    <x-button label="Cancelar" @click="$wire.modal = false"/>
    <x-button label="Confirmar" class="btn-primary" wire:click="destroy" spinner class="btn-danger"/>
</x-modal>

