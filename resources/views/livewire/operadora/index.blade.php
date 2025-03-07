<div>
    <!-- HEADER -->
    <x-header title="Operadoras" separator progress-indicator>

        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button @click="$wire.filtros = true" responsive icon="o-funnel" class="btn-primary"
                      icon="o-funnel" tooltip-bottom="Filtros"/>
            <x-button icon="o-plus" class="btn-primary" wire:navigate href="{{ route('operadora.create') }}"
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

        <x-checkbox label="Buscar por operadora excluidas" wire:model.live.debounce="search_trash"
                    hint="Ative para buscar por empresas excluidas"/>
        <x-input label="Razao social" wire:model="razao_social" wire:model.live.debounce="razao_social"/>
        <x-input label="CNPJ" wire:model="cnpj" wire:model.live.debounce="cnpj"/>


        <x-slot:actions>
            <x-button label="Filtar" class="btn-primary" icon="o-check" @click="$wire.filtros = false"/>
        </x-slot:actions>
    </x-drawer>

    <!-- TABLE  -->
    <x-card>

        <x-table :headers="$this->headers" :rows="$this->operadoras" with-pagination per-page="perPage"
                 :per-page-values="[3, 5, 10]" :sort-by="$sortBy">
            @scope('cell_razao_social', $operadora)
            {{$operadora->razao_social}}
            @endscope

            @scope('cell_nome_fantasia', $operadora)
            {{$operadora->nome_fantasia}}
            @endscope

            @permission('incluir')
            @scope('actions', $operadora)
            <span class="flex">


                <x-button icon="o-building-office-2" wire:navigate
                          href="{{ route('operadora.edit', ['id' => $operadora->id])  }}" spinner
                          class="btn-ghost btn-sm text-white-500" tooltip="Convenio"/>
                <x-button icon="o-pencil-square" wire:navigate
                          href="{{ route('operadora.edit', ['id' => $operadora->id])  }}" spinner
                          class="btn-ghost btn-sm text-white-500" tooltip="Editar"/>



                <x-button
                    icon="o-document-magnifying-glass" wire:navigate
                    href="{{ route('operadora.show', ['id' => $operadora->id])  }}" spinner
                    class="btn-ghost btn-sm text-white-500" tooltip="Visualizar"/>

                @unless($operadora->trashed())
                    <x-button
                        id="delete-btn-{{ $operadora->id }}"
                        wire:key="delete-btn-{{ $operadora->id }}"
                        icon="o-trash"
                        wire:click="destroy('{{ $operadora->id }}')"
                        spinner
                        class="btn-ghost btn-sm text-red-500" tooltip="Apagar"
                    />
                @else
                    <x-button
                        id="restore-btn-{{ $operadora->id }}"
                        wire:key="restore-btn-{{ $operadora->id }}"
                        icon="o-arrow-path-rounded-square"
                        wire:click="restore('{{ $operadora->id }}')"
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

    <livewire:operadora.delete/>
    <livewire:operadora.restore/>
</div>
