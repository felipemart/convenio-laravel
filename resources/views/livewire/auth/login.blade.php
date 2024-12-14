<x-card title="Login" shadow class="mx-auto w-[400px]">


    <x-form wire:submit="tryLogin">
        <x-input label="Email" wire:model="email"/>
        <x-input label="Senha" wire:model="password" type="password"/>

        @if($message = session()->has('status'))
            <x-alert icon="o-exclamation-triangle" class="alert-warning">
                <span>{{ $message }}</span>
            </x-alert>
        @endif


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
        <div class="w-full flex items-center justify-between">


            <x-slot:actions>
                <a wire:navigate href="{{ route('password.recovery')  }}" class="btn btn-ghost"> Esqueci minha senha</a>
                <x-button label="Cancelar" type="reset"/>
                <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>
        </div>
    </x-form>

</x-card>
