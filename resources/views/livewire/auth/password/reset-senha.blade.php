<x-card title="Recuperação de Senha" shadow-sm class="mx-auto w-[350px]">

    <x-form wire:submit="resetarSenha">
        <x-input label="Email" value="{{ $this->obfuscarEmail }}" readonly/>
        <x-input label="Senha nova" wire:model="password" type="password"/>
        <x-input label="Confirmar senha" wire:model="password_confirmation" type="password"/>

        @if(session()->has('status'))
            <x-alert icon="o-exclamation-triangle" class="alert-warning">
                <span>{{ session()->get('status') }}</span>
            </x-alert>
        @endif


        <div class="w-full flex items-center justify-between">
            <x-slot:actions>
                <a wire:navigate href="/login" class="btn btn-ghost">Voltar</a>
                <x-button label="Cancelar" type="reset"/>
                <x-button label="Resetar" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </div>
    </x-form>
</x-card>
