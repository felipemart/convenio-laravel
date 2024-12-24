<div>
    <!-- HEADER -->
    <x-header title="Usuarios" separator progress-indicator>
        <x-slot:middle class="!justify-end">

            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" class="btn-primary"/>
            <x-theme-toggle class="btn btn-circle"/>
        </x-slot:actions>
    </x-header>

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
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3">
        <x-input placeholder="Search..." wire:model.live.debounce="search" icon="o-magnifying-glass"
                 @keydown.enter="$wire.drawer = false"/>

        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner/>
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false"/>
        </x-slot:actions>
    </x-drawer>
</div>
