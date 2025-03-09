<div>
    <!-- HEADER -->
    <x-header title="Convenio" separator progress-indicator>

        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button @click="$wire.filtros = true" responsive icon="o-funnel" class="btn-primary"
                      icon="o-funnel" tooltip-bottom="Filtros"/>

            <x-button icon="o-plus" class="btn-primary" wire:navigate href="{{ route('conveniada.create') }}"
                      tooltip-bottom="Cadastrar"/>
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

        <x-checkbox label="Buscar por conveniada excluidas" wire:model.live.debounce="search_trash"
                    hint="Ative para buscar por empresas excluidas"/>
        <x-input label="Razao social" wire:model="razao_social" wire:model.live.debounce="razao_social"/>
        <x-input label="CNPJ" wire:model="cnpj" wire:model.live.debounce="cnpj"/>


        <x-slot:actions>
            <x-button label="Filtar" class="btn-primary" icon="o-check" @click="$wire.filtros = false"/>
        </x-slot:actions>
    </x-drawer>

    <!-- TABLE  -->
    <x-card>

        <x-table :headers="$this->headers" :rows="$this->conveniadas" with-pagination per-page="perPage"
                 :per-page-values="[3, 5, 10]" :sort-by="$sortBy">
            @scope('cell_razao_social', $conveniada)
            {{$conveniada->razao_social}}
            @endscope

            @scope('cell_nome_fantasia', $conveniada)
            {{$conveniada->nome_fantasia}}
            @endscope

            @permission('incluir')
            @scope('actions', $conveniada)
            <span class="flex">
                <x-button icon="o-users" wire:navigate
                          href="{{ route('conveniada.list', ['id' => $conveniada->id])  }}" spinner
                          class="btn-ghost btn-sm text-white-500" tooltip="Clientes"/>
                <x-button icon="o-pencil-square" wire:navigate
                          href="{{ route('conveniada.edit', ['id' => $conveniada->id])  }}" spinner
                          class="btn-ghost btn-sm text-white-500" tooltip="Editar"/>



                <x-button
                    icon="o-document-magnifying-glass" wire:navigate
                    href="{{ route('conveniada.show', ['id' => $conveniada->id])  }}" spinner
                    class="btn-ghost btn-sm text-white-500" tooltip="Visualizar"/>

                @unless($conveniada->trashed())
                    <x-button
                        id="delete-btn-{{ $conveniada->id }}"
                        wire:key="delete-btn-{{ $conveniada->id }}"
                        icon="o-trash"
                        wire:click="destroy('{{ $conveniada->id }}')"
                        spinner
                        class="btn-ghost btn-sm text-red-500" tooltip="Apagar"
                    />
                @else
                    <x-button
                        id="restore-btn-{{ $conveniada->id }}"
                        wire:key="restore-btn-{{ $conveniada->id }}"
                        icon="o-arrow-path-rounded-square"
                        wire:click="restore('{{ $conveniada->id }}')"
                        spinner
                        class="btn-ghost btn-sm text-white-500" tooltip="Reativar"
                    />
                @endunless
            </span>
            @endscope
            @endpermission


        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->

    <livewire:conveniada.delete/>
    <livewire:conveniada.restore/>
</div>
