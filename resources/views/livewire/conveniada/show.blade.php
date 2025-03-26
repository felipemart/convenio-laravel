<div>

    <x-header title="Dados da Conveniada:" separator progress-indicator>


    </x-header>


    <x-card>
        @if($conveniada)
            <x-tabs wire:model="selectedTab">
                <x-tab name="users-tab" label="Informações" icon="o-users">

                    <x-input readonly label="CNPJ" :value="$conveniada->empresa->cnpj" class=""/>
                    <x-input readonly label="Nome Fantasia" :value="$conveniada->empresa->nome_fantasia"/>
                    <x-input readonly label="Razao Social" :value="$conveniada->empresa->razao_social" class=""/>
                    <x-input readonly label="Logradouro" :value="$conveniada->empresa->logradouro" class=""/>
                    <x-input readonly label="Bairro" :value="$conveniada->empresa->bairro" class=""/>
                    <x-input readonly label="CEP" :value="$conveniada->empresa->cep" class=""/>
                    <x-input readonly label="UF" :value="$conveniada->empresa->uf" class=""/>
                    <x-input readonly label="Cidade" :value="$conveniada->empresa->cidade" class=""/>
                    <x-input readonly label="Email" :value="$conveniada->empresa->email" class=""/>
                    <x-input readonly label="Data de criação"
                             :value="$conveniada->empresa->created_at->format('d/m/Y H:i')"
                             class=""/>
                    <x-input readonly label="Data da ultima atualização"
                             :value="$conveniada->empresa->updated_at->format('d/m/Y H:i')"
                             class=""/>
                    @if($conveniada->deleted_at)
                        <x-input readonly label="Data de Restaurasão"
                                 :value="$conveniada->deleted_at?->format('d/m/Y H:i')"
                                 class=""/>
                        <x-input readonly label="Deletado por" :value="$conveniada->deletedBy?->name"
                                 class=""/>
                    @endif
                    @if($conveniada->restored_at)
                        <x-input readonly label="Data de Restaurado"
                                 :value="$conveniada->restored_at?->format('d/m/Y H:i')"
                                 class=""/>
                        <x-input readonly label="Deletado por" :value="$conveniada->restoredBy?->name"
                                 class=""/>
                    @endif


                </x-tab>
                <x-tab name="tricks-tab" label="Contatos" icon="o-sparkles">
                    <div>Tricks</div>
                </x-tab>


                <x-tab name="conveniada-tab" label="Convenio" icon="o-sparkles">
                    <div>config?</div>
                </x-tab>


            </x-tabs>

        @endif
        <br/>
        <x-button label="Voltar" wire:navigate href="{{ route('conveniada.list', ['id'  =>  $this->convenioId])  }}"/>

    </x-card>
</div>
