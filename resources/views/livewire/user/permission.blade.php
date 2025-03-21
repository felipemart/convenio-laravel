<div>
    <!-- HEADER -->
    <x-header title="Permissão" separator progress-indicator>

        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
    </x-header>

    <!-- TABLE  -->
    <x-card>

        <div>
            <x-table :headers="$this->headers" :rows="$this->permissions" with-pagination per-page="perPage"
                     :per-page-values="[3, 5, 10]" :sort-by="$sortBy">
                @scope('actions', $permissions)
                <span class="flex">
                            <x-toggle
                                id="{{ $permissions->id }}"
                                wire:model="setPermissions.{{ $permissions->id }}"
                                value="{{ $permissions->id }}"
                                class="toggle-primary" @change="$wire.updatePermissions({{ $permissions->id }})" right
                                tight/>
                        </span>
                @endscope
            </x-table>

        </div>
    </x-card>

    <x-button wire:navigate href="{{ route('user.list')  }}"
              label="Voltar"/>
    <!-- FILTER DRAWER -->

</div>
