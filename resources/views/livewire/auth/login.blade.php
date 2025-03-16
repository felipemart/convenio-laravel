<x-card title="Login" shadow class="mx-auto w-[500px]">


    <x-form wire:submit="lgoin">
        <x-input label="Email" wire:model="email"/>
        <x-input label="Senha" wire:model="password" type="password"/>

        @if(session()->has('status'))
            <x-alert icon="o-exclamation-triangle" class="alert-warning">
                <span>{{ session()->get('status') }}</span>
            </x-alert>
        @endif

        @if($errors->hasAny(['crendenciaisInvalidas', 'rateLimiter']))
            <x-alert icon="o-exclamation-triangle" class="alert-warning">

                @error('crendenciaisInvalidas')
                <span>{{ $message }}</span>
                @enderror
                @error('rateLimiter')
                <span>{{ $message }}</span>
                @enderror

            </x-alert>

        @endif
        <div class="w-full flex items-center justify-between">
            <a wire:navigate href="{{ route('password.recovery')  }}"
               class="text-xs text-gray-3   00 hover:underline dark:text-gray-200">
                Esqueci minha senha</a>
            <x-slot:actions>

                <x-button label="Cancelar" type="reset"/>
                <x-button label="Login" class="btn-primary" type="submit" spinner="save"/>
            </x-slot:actions>

        </div>
    </x-form>

</x-card>
