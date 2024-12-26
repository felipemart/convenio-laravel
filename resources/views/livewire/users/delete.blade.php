<div>
    <x-modal wire:model="modal" class="backdrop-blur">
        <div class="mb-5">Press `ESC`, click outside or click `CANCEL` to close.</div>
        <x-button label="Cancel" @click="$wire.modal = false"/>
    </x-modal>

    <x-button label="Open" @click="$wire.modal = true"/>
</div>
