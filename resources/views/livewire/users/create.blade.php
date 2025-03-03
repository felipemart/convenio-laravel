<div>
    <!-- HEADER -->
    <x-header title="Cadastro de usuário" separator progress-indicator>


    </x-header>
    <!-- TABLE  -->
    <x-card>
        <x-steps wire:model="step" class=" my-5 p-5">
            <x-step step="1" text="Register">
                <div>
                    <x-form wire:submit="save">
                        <x-input label="Nome" wire:model="name" class="mb-2"/>
                        <x-input label="Email" wire:model="email" class="mb-2"/>

                        <x-select
                            label="Nivel de acesso"
                            :options="$roles"
                            option-value="id"
                            option-label="name"
                            placeholder="Selecionar"
                            placeholder-value=""
                            wire:change="changeEmpresa"
                            wire:model="roleSelect" class="mb-2"/>

                        <x-select
                            label="Empresa"
                            :options="$empresa"
                            option-value="id"
                            option-label="nome_fantasia"
                            placeholder="Selecionar"
                            placeholder-value=""
                            wire:model="empresaSelect" class="mb-2"/>

                    </x-form>
                </div>
            </x-step>
            <x-step step="2" text="Permissão">
                <div>
                    <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable
                             icon="o-magnifying-glass"/>
                    <x-table :headers="$this->headers" :rows="$this->permissions" with-pagination per-page="perPage"
                             :per-page-values="[3, 5, 10]" :sort-by="$sortBy">
                        @scope('actions', $permissions)
                        <span class="flex">
                            <x-toggle
                                wire:model="setPermissions.{{ $permissions->id }}"
                                value="{{ $permissions->id }}"
                                class="toggle-primary" @change="$wire.updatePermissions({{ $permissions->id }})" right
                                tight/>
                        </span>
                        @endscope
                    </x-table>

                </div>
            </x-step>
        </x-steps>

        <hr class="my-5"/>
        <x-button wire:navigate href="{{ route('user.list')  }}"
                  label="Cancelar"/>
        <x-button label="{{ $step == 1 ? 'Proximo' : 'Salvar' }}" wire:click="next"/>


    </x-card>


</div>
