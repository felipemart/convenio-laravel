<div>

    <x-header title="Dados da Empresa:" separator progress-indicator>


    </x-header>


    <x-card>
        @if($empresa)
            <x-tabs wire:model="selectedTab">
                <x-tab name="users-tab" label="Informações" icon="o-users">

                    <x-input readonly label="CNPJ" :value="$empresa->cnpj" class="mb-2"/>
                    <x-input readonly label="Nome Fantasia" :value="$empresa->nome_fantasia"/>
                    <x-input readonly label="Razao Social" :value="$empresa->razao_social" class="mb-2"/>
                    <x-input readonly label="Logradouro" :value="$empresa->logradouro" class="mb-2"/>
                    <x-input readonly label="Bairro" :value="$empresa->bairro" class="mb-2"/>
                    <x-input readonly label="CEP" :value="$empresa->cep" class="mb-2"/>
                    <x-input readonly label="UF" :value="$empresa->uf" class="mb-2"/>
                    <x-input readonly label="Cidade" :value="$empresa->cidade" class="mb-2"/>
                    <x-input readonly label="Email" :value="$empresa->email" class="mb-2"/>
                    <x-input readonly label="Data de criação"
                             :value="$empresa->created_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    <x-input readonly label="Data da ultima atualização"
                             :value="$empresa->updated_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    @if($empresa?->deleted_at)
                        <x-input readonly label="Data de exclusão"
                                 :value="$empresa->deleted_at?->format('d/m/Y H:i')"
                                 class="mb-2"/>
                        <x-input readonly label="Deletado por" :value="$empresa->deletedBy?->name" class="mb-2"/>
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
