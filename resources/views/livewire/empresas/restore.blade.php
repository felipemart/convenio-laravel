<x-modal wire:model="modal" title="Restaurar a Empresa {{ $empresa?->nome_fantasia }}?" class="backdrop-blur">
    @error('confirmRestore')
    <x-alert icon="o-exclamation-triangle" class="alert-warning">
        <span>{{ $message }}</span>
    </x-alert>
    @enderror

    <x-input label="Para restaurar o usuario digite 'RESTAURAR' " value="" wire:model="confirmRestore_confirmation"/>
    <br/>
    <x-button label="Cancelar" @click="$wire.modal = false"/>
    <x-button label="Confirmar" class="btn-primary" wire:click="restore" spinner class="btn-danger"/>
</x-modal>

