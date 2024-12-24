<div>
    <!-- HEADER -->
    <x-header title="Usuarios" separator progress-indicator>
        <x-slot:middle class="!justify-end">

            <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.filtros = true" responsive icon="o-funnel" class="btn-primary"/>

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

        <x-input label="Nome" wire:model="nome" wire:model.live.debounce="nome"/>
        <x-input label="Email" wire:model="email" wire:model.live.debounce="email"/>
        <x-choices
            label="Nivel"
            wire:model.live.debounce="searchRole"
            :options="$roleToSearch"
            placeholder="Search ..."
            search-function="filterRole"
            no-result-text="Ops! Nothing here ..."
            option-label="role"
            searchable/>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.showDrawer3 = false"/>
            <x-button label="Confirm" class="btn-primary" icon="o-check"/>
        </x-slot:actions>
    </x-drawer>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$this->headers" :rows="$this->users" with-pagination per-page="perPage"
                 :per-page-values="[3, 5, 10]" :sort-by="$sortBy">
            @scope('cell_roles', $user)
            @foreach($user->roles as $role)
                <x-badge :value="$role->role" class="badge-primary"/>
            @endforeach
            @endscope
            @permission('incluir')
            @scope('actions', $user)
            <span class="flex">
                    <x-button icon="o-document-magnifying-glass" wire:click="delete({{ $user['id'] }})" spinner
                              class="btn-ghost btn-sm text-white-500" tooltip="Visualizar"/>
                    <x-button icon="o-pencil-square" wire:click="delete({{ $user['id'] }})" spinner
                              class="btn-ghost btn-sm text-white-500" tooltip="Editar"/>
                    <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" spinner
                              class="btn-ghost btn-sm text-red-500" tooltip="Apagar"/>
                </span>
            @endscope
            @endpermission


        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->

</div>
