<div>

    <x-header title="Dados da Operadora:" separator progress-indicator>


    </x-header>


    <x-card>
        @if($operadora)
            <x-tabs wire:model="selectedTab">
                <x-tab name="users-tab" label="Informações" icon="o-users">

                    <x-input readonly label="CNPJ" :value="$operadora->empresa->cnpj" class="mb-2"/>
                    <x-input readonly label="Nome Fantasia" :value="$operadora->empresa->nome_fantasia"/>
                    <x-input readonly label="Razao Social" :value="$operadora->empresa->razao_social" class="mb-2"/>
                    <x-input readonly label="Logradouro" :value="$operadora->empresa->logradouro" class="mb-2"/>
                    <x-input readonly label="Bairro" :value="$operadora->empresa->bairro" class="mb-2"/>
                    <x-input readonly label="CEP" :value="$operadora->empresa->cep" class="mb-2"/>
                    <x-input readonly label="UF" :value="$operadora->empresa->uf" class="mb-2"/>
                    <x-input readonly label="Cidade" :value="$operadora->empresa->cidade" class="mb-2"/>
                    <x-input readonly label="Email" :value="$operadora->empresa->email" class="mb-2"/>
                    <x-input readonly label="Data de criação"
                             :value="$operadora->empresa->created_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    <x-input readonly label="Data da ultima atualização"
                             :value="$operadora->empresa->updated_at->format('d/m/Y H:i')"
                             class="mb-2"/>
                    @if($operadora->deleted_at)
                        <x-input readonly label="Data de Restaurasão"
                                 :value="$operadora->deleted_at?->format('d/m/Y H:i')"
                                 class="mb-2"/>
                        <x-input readonly label="Deletado por" :value="$operadora->deletedBy?->name"
                                 class="mb-2"/>
                    @endif
                    @if($operadora->restored_at)
                        <x-input readonly label="Data de Restaurado"
                                 :value="$operadora->restored_at?->format('d/m/Y H:i')"
                                 class="mb-2"/>
                        <x-input readonly label="Deletado por" :value="$operadora->restoredBy?->name"
                                 class="mb-2"/>
                    @endif


                </x-tab>
                <x-tab name="tricks-tab" label="Contatos" icon="o-sparkles">
                    <div>Tricks</div>
                </x-tab>


                <x-tab name="operadora-tab" label="Operadora" icon="o-sparkles">
                    <div>config?</div>
                </x-tab>


            </x-tabs>

        @endif
        <br/>
        <x-button label="Cancelar" wire:navigate href="{{ route('operadora.list')  }}"/>

    </x-card>
</div>
