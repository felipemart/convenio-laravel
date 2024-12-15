<x-card title="Recuperação de Senha" shadow class="mx-auto w-[350px]">

    @if($message)
        <x-alert icon="o-check-circle" class="alert-success">
            <span>{{ $message }}</span>
        </x-alert>
    @endif

    <x-form wire:submit="recuperacaoSenha">
        <x-input label="Email" wire:model="email"/>
        <div class="w-full flex items-center justify-between">
            <x-slot:actions>
                <a wire:navigate href="/login" class="btn btn-ghost">Voltar</a>
                <x-button label="Cancelar" type="reset"/>
                <x-button label="Recuperar" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </div>
    </x-form>
</x-card>
