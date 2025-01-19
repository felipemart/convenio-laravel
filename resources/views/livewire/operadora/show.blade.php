<div>

    <x-header title="Dados da Operadora:" separator progress-indicator>


    </x-header>


    <x-card>
        @if($operadora)
            <x-tabs wire:model="selectedTab">
                <x-tab name="users-tab" label="Informações" icon="o-users">

                    <x-input readonly label="CNPJ" :value="$operadora->empresas->cnpj" class="mb-2"/>
                    <x-input readonly label="Nome Fantasia" :value="$operadora->empresas->nome_fantasia"/>
                    <x-input readonly label="Razao Social" :value="$operadora->empresas->razao_social" class="mb-2"/>
                    <x-input readonly label="Logradouro" :value="$operadora->empresas->logradouro" class="mb-2"/>
                    <x-input readonly label="Bairro" :value="$operadora->empresas->bairro" class="mb-2"/>
                    <x-input readonly label="CEP" :value="$operadora->empresas->cep" class="mb-2"/>
                    <x-input readonly label="UF" :value="$operadora->empresas->uf" class="mb-2"/>
                    <x-input readonly label="Cidade" :value="$operadora->empresas->cidade" class="mb-2"/>
                    <x-input readonly label="Email" :value="$operadora->empresas->email" class="mb-2"/>
                    <x-input readonly label="Data de criação"
                             :value="$operadora->empresas->created_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    <x-input readonly label="Data da ultima atualização"
                             :value="$operadora->empresas->updated_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    @if($operadora?->deleted_at)
                        <x-input readonly label="Data de exclusão"
                                 :value="$operadora->empresas->deleted_at?->format('d/m/Y H:i')"
                                 class="mb-2"/>
                        <x-input readonly label="Deletado por" :value="$operadora->deletedBy?->name" class="mb-2"/>
                    @endif


                </x-tab>
                <x-tab name="tricks-tab" label="Contatos" icon="o-sparkles">
                    <div>Tricks</div>
                </x-tab>

            </x-tabs>

        @endif
        <br/>
        <x-button label="Cancelar" @click="$wire.modal = false"/>

    </x-card>
</div>
