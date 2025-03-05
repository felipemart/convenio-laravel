<div>
    <!-- HEADER -->
    <x-header title="Empresas" separator progress-indicator>

        <x-slot:middle class="!justify-end">
            <x-input placeholder="Pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass"/>
        </x-slot:middle>
        <x-slot:actions>
            <x-button @click="$wire.filtros = true" responsive icon="o-funnel" class="btn-primary"
                      icon="o-funnel" tooltip-bottom="Filtros"/>
            <x-button icon="o-plus" class="btn-primary" wire:navigate href="{{ route('empresas.create') }}"
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

        <x-checkbox label="Buscar por empresas excluidas" wire:model.live.debounce="search_trash"
                    hint="Ative para buscar por empresas excluidas"/>
        <x-input label="Razao social" wire:model="razao_social" wire:model.live.debounce="razao_social"/>
        <x-input label="CNPJ" wire:model="cnpj" wire:model.live.debounce="cnpj"/>


        <x-slot:actions>
            <x-button label="Filtar" class="btn-primary" icon="o-check" @click="$wire.filtros = false"/>
        </x-slot:actions>
    </x-drawer>

    <!-- TABLE  -->
    <x-card>

        <x-table :headers="$this->headers" :rows="$this->empresas" with-pagination per-page="perPage"
                 :per-page-values="[3, 5, 10]" :sort-by="$sortBy">
            @scope('cell_razao_social', $empresa)
            {{ $empresa->razao_social }}
            @endscope
            @scope('cell_role_id', $empresa)

            @if ($empresa->operadora->isNotEmpty())
                {{-- Verifica se a empresa é uma operadora --}}
                <x-badge value="Operadora" class="bg-purple-500/50"/>
                @if ($empresa->convenios->isNotEmpty())
                    {{-- Verifica se a operadora tem convênios --}}
                    @foreach ($empresa->convenios as $convenio)
                        <x-badge value="Convênio" class="bg-blue-300/30"/>
                    @endforeach
                @endif
                @if ($empresa->conveniadas->isNotEmpty())
                    {{-- Verifica se a operadora tem conveniadas --}}
                    @foreach ($empresa->conveniadas as $conveniada)
                        <x-badge value="Conveniada" class="bg-green-300/30"/>
                    @endforeach
                @endif
            @elseif ($empresa->convenios->isNotEmpty())
                {{-- Verifica se a empresa é um convênio --}}
                <x-badge value="Convênio" class="bg-blue-300/30"/>
                @if ($empresa->conveniadas->isNotEmpty())
                    {{-- Verifica se o convênio tem conveniadas --}}
                    @foreach ($empresa->conveniadas as $conveniada)
                        <x-badge value="Conveniada" class="bg-green-300/30"/>
                    @endforeach
                @endif
            @elseif ($empresa->conveniadas->isNotEmpty())
                {{-- Verifica se a empresa é uma conveniada --}}
                <x-badge value="Conveniada" class="bg-green-300/30"/>
            @endif

            {{--  Tratamento para Admin (se necessário) --}}
            @if ($empresa->id === 1)
                {{-- Ou outra condição que identifique o admin --}}
                <x-badge value="Admin" class="badge-primary"/>
            @endif

            @endscope
            @permission('incluir')
            @scope('actions', $empresa)
            <span class="flex">

            @if ($empresa->conveniadas->isNotEmpty())
                    <x-button icon="o-user-group" wire:navigate
                              href="#" spinner
                              class="btn-ghost btn-sm text-white-500" tooltip="Clientes"/>
                @endif
                <x-button icon="o-pencil-square" wire:navigate
                          href="{{ route('user.edit', ['id' => $empresa->id])  }}" spinner
                          class="btn-ghost btn-sm text-white-500" tooltip="Editar"/>



                <x-button
                    icon="o-document-magnifying-glass" wire:navigate
                    href="{{ route('empresas.show', ['id' => $empresa->id])  }}" spinner
                    class="btn-ghost btn-sm text-white-500" tooltip="Visualizar"/>


                @unless($empresa->trashed())
                    <x-button
                        id="delete-btn-{{ $empresa->id }}"
                        wire:key="delete-btn-{{ $empresa->id }}"
                        icon="o-trash"
                        wire:click="destroy('{{ $empresa->id }}')"
                        spinner
                        class="btn-ghost btn-sm text-red-500" tooltip="Apagar"
                    />
                @else
                    <x-button
                        id="restore-btn-{{ $empresa->id }}"
                        wire:key="restore-btn-{{ $empresa->id }}"
                        icon="o-arrow-path-rounded-square"
                        wire:click="restore('{{ $empresa->id }}')"
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

    <livewire:empresas.delete/>
    <livewire:empresas.restore/>
</div>
