<div>
    <!-- HEADER -->
    <x-header title="Dados do usuário: {{$user->name}}" separator progress-indicator>


    </x-header>


    <!-- TABLE  -->
    <x-card>
        <x-tabs wire:model="selectedTab">
            <x-tab name="users-tab" label="Dados" icon="o-users">
                <div>
                    <x-form wire:submit="save">
                        <x-input label="Nome" value="{{$user->name}}" class="mb-2"/>
                        <x-input label="Email" value="{{$user->email}}" class="mb-2"/>

                        <x-select
                            label="Nivel de acesso"
                            :options="$roles"
                            option-value="id"
                            option-label="role"
                            placeholder="Selecionar"
                            placeholder-value="1"
                            wire:model="roleSelect" class="mb-2"/>


                        <x-input label="Criado em" value="{{$user->created_at}}" class="mb-2" disabled/>
                        @if($user->deleted_at)
                            <x-input label="Deletado em" value="{{$user->deleted_at}}" class="mb-2" disabled/>
                        @endif

                        @if($user->restored_at)
                            <x-input label="Restaurado em" value="{{$user->restored_at}}" class="mb-2" disabled/>
                        @endif

                        <x-slot:actions>
                            <x-button label="Cancel"/>
                            <x-button label="Atualizar dados" class="btn-primary" type="submit" spinner="save"/>
                        </x-slot:actions>
                    </x-form>
                </div>


            </x-tab>
            <x-tab name="permissions-tab" label="Permissões" icon="o-sparkles">
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
            </x-tab>
        </x-tabs>

    </x-card>

    <!-- FILTER DRAWER -->

</div>
