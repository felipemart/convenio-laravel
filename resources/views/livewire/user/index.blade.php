<div>
    <!-- HEADER -->
    <x-header title="Usuarios" separator progress-indicator>

        <x-slot:middle class="justify-end!">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button @click="$wire.filtros = true" responsive icon="o-funnel" class="btn-primary"
                      icon="o-funnel" tooltip-bottom="Filtros"/>

            @permission('usuario.create')
            <x-button icon="o-plus" class="btn-primary" wire:navigate href="{{ route('user.create') }}"
                      tooltip-bottom="Cadastrar"/>
            @endhaspermission
        </x-slot:actions>
    </x-header>

    <x-drawer
        wire:model="filtros"
        title="Filtros"
        separator
        with-close-button
        close-on-escape
        class="w-11/12 lg:w-1/3"
        right
    >

        <x-checkbox label="Buscar pelo usuarios excluidos" wire:model.live.debounce="search_trash"
                    hint="Ative para buscar usuarios excluidos"/>
        <x-input label="Nome" wire:model="nome" wire:model.live.debounce="nome"/>
        <x-input label="Email" wire:model="email" wire:model.live.debounce="email"/>
        <x-choices
            label="Nivel"
            wire:model.live.debounce="searchRole"
            :options="$roleToSearch"
            placeholder="Search ..."
            search-function="filterRole"
            no-result-text="Ops! Nothing here ..."
            option-label="name"
            searchable/>


        <x-slot:actions>
            <x-button label="Filtar" class="btn-primary" icon="o-check" @click="$wire.filtros = false"/>
        </x-slot:actions>
    </x-drawer>

    <!-- TABLE  -->
    <x-card>

        <x-table :headers="$this->headers" :rows="$this->users" with-pagination per-page="perPage"
                 :per-page-values="[3, 5, 10]" :sort-by="$sortBy">
            @scope('cell_roles', $user)
            {{ $user->role->name }}
            @endscope
            @scope('cell_empresa', $user)
            {{ $user->empresa->nome_fantasia }}
            @endscope

            @scope('actions', $user)
            <span class="flex">
                  @permission('usuario.edit')
                    <x-button icon="o-pencil-square" wire:navigate
                              href="{{ route('user.edit', ['id' => $user->id])  }}" spinner
                              class="btn-ghost btn-sm text-white-500" tooltip="Editar"/>
                @endpermission
                @permission('usuario.permission')
                    <x-button icon="o-adjustments-horizontal" wire:navigate
                              href="{{ route('user.permissions', ['id' => $user->id])  }}" spinner
                              class="btn-ghost btn-sm text-white-500" tooltip="Permissão"/>
                @endpermission
                   <x-button
                       id="show-btn-{{ $user->id }}"
                       wire:key="show-btn-{{ $user->id }}"
                       icon="o-document-magnifying-glass"
                       wire:click="show('{{ $user->id }}')"
                       spinner
                       class="btn-ghost btn-sm text-white-500" tooltip="Visualizar"
                   />

                @permission('usuario.delete')
                @unless($user->trashed())
                    @unless($user->is(auth()->user()))
                        <x-button
                            id="delete-btn-{{ $user->id }}"
                            wire:key="delete-btn-{{ $user->id }}"
                            icon="o-trash"
                            wire:click="destroy('{{ $user->id }}')"
                            spinner
                            class="btn-ghost btn-sm text-red-500" tooltip="Apagar"
                        />
                    @endif
                @else
                    <x-button
                        id="restore-btn-{{ $user->id }}"
                        wire:key="restore-btn-{{ $user->id }}"
                        icon="o-arrow-path-rounded-square"
                        wire:click="restore('{{ $user->id }}')"
                        spinner
                        class="btn-ghost btn-sm text-white-500" tooltip="Reativar"
                    />
                @endunless
                @endpermission
            </span>
            @endscope


        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->

    <livewire:user.delete/>
    <livewire:user.restore/>
    <livewire:user.show/>
</div>
