<div>
    <!-- HEADER -->
    <x-header title="Cadastro de usuário" separator progress-indicator>
    </x-header>
    <!-- TABLE  -->
    <x-card>
        <div>
            <x-form wire:submit="save">
                <x-input label="Nome" wire:model="name" class=""/>
                <x-input label="Email" wire:model="email" class=""/>

                <x-select
                    label="Nivel de acesso"
                    :options="$roles"
                    option-value="id"
                    option-label="name"
                    placeholder="Selecionar"
                    placeholder-value=""
                    wire:change="changeEmpresa"
                    wire:model="roleSelect" class=""/>

                <x-select
                    label="Empresa"
                    :options="$empresa"
                    option-value="id"
                    option-label="nome_fantasia"
                    placeholder="Selecionar"
                    placeholder-value=""
                    wire:model="empresaSelect" class=""/>

            </x-form>
        </div>

        <hr class="my-5"/>
        <x-button wire:navigate href="{{ route('user.list')  }}"
                  label="Cancelar"/>
        <x-button label="Salvar" wire:click="save"/>


    </x-card>


</div>
