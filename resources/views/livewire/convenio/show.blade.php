<div>

    <x-header title="Dados da Convenio:" separator progress-indicator>


    </x-header>


    <x-card>
        @if($convenio)
            <x-tabs wire:model="selectedTab">
                <x-tab name="users-tab" label="Informações" icon="o-users">

                    <x-input readonly label="CNPJ" :value="$convenio->empresa->cnpj" class="mb-2"/>
                    <x-input readonly label="Nome Fantasia" :value="$convenio->empresa->nome_fantasia"/>
                    <x-input readonly label="Razao Social" :value="$convenio->empresa->razao_social" class="mb-2"/>
                    <x-input readonly label="Logradouro" :value="$convenio->empresa->logradouro" class="mb-2"/>
                    <x-input readonly label="Bairro" :value="$convenio->empresa->bairro" class="mb-2"/>
                    <x-input readonly label="CEP" :value="$convenio->empresa->cep" class="mb-2"/>
                    <x-input readonly label="UF" :value="$convenio->empresa->uf" class="mb-2"/>
                    <x-input readonly label="Cidade" :value="$convenio->empresa->cidade" class="mb-2"/>
                    <x-input readonly label="Email" :value="$convenio->empresa->email" class="mb-2"/>
                    <x-input readonly label="Data de criação"
                             :value="$convenio->empresa->created_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    <x-input readonly label="Data da ultima atualização"
                             :value="$convenio->empresa->updated_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    @if($convenio->deleted_at)
                        <x-input readonly label="Data de Restaurasão"
                                 :value="$convenio->deleted_at?->format('d/m/Y H:i')"
                                 class="mb-2"/>
                        <x-input readonly label="Deletado por" :value="$convenio->deletedBy?->name"
                                 class="mb-2"/>
                    @endif
                    @if($convenio->restored_at)
                        <x-input readonly label="Data de Restaurado"
                                 :value="$convenio->restored_at?->format('d/m/Y H:i')"
                                 class="mb-2"/>
                        <x-input readonly label="Deletado por" :value="$convenio->restoredBy?->name"
                                 class="mb-2"/>
                    @endif


                </x-tab>
                <x-tab name="tricks-tab" label="Contatos" icon="o-sparkles">
                    <div>Tricks</div>
                </x-tab>


                <x-tab name="convenio-tab" label="Convenio" icon="o-sparkles">
                    <div>config?</div>
                </x-tab>


            </x-tabs>

        @endif
        <br/>
        <x-button label="Cancelar" wire:navigate href="{{ route('convenio.list')  }}"/>

    </x-card>
</div>
