<x-card title="Login" shadow class="mx-auto w-[350px]">


    <x-form wire:submit="tryLogin">
        <x-input label="Email" wire:model="email"/>
        <x-input label="Senha" wire:model="password" type="password"/>

        @if($errors->hasAny(['invalidCredentials', 'rateLimiter']))
            <x-alert icon="o-exclamation-triangle" class="alert-warning">

                @error('invalidCredentials')

                <span>{{ $message }}</span>

                @enderror

                @error('rateLimiter')

                <span>{{ $message }}</span>

                @enderror

            </x-alert>

        @endif
        <x-slot:actions>
            <x-button label="Cancelar" type="reset"/>
            <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
        </x-slot:actions>
    </x-form>

</x-card>
