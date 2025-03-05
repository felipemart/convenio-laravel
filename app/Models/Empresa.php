<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cnpj',
        'nome_fantasia',
        'razao_social',
        'abreviatura',
        'cep',
        'logradouro',
        'bairro',
        'cidade',
        'uf',
        'telefone',
        'email',
        'inscricao_estadual',
        'inscricao_municipal',
        'deleted_by',
        'restored_by',

    ];

    public function operadora(): HasMany
    {
        return $this->hasMany(Operadora::class);
    }

    public function convenios(): HasMany
    {
        return $this->hasMany(Convenio::class);
    }

    public function conveniadas(): HasMany
    {
        return $this->hasMany(Conveniada::class);
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function giveOperadora(): void
    {
        $this->operadora()->firstOrCreate(['empresa_id' => $this->id]);
    }

    public function giveConvenio(int $empresaId): void
    {
        $idOperadora = Operadora::where('empresa_id', $empresaId)->first()->id;

        if ($idOperadora) {
            $this->convenios()->firstOrCreate(['empresa_id' => $this->id, 'operadora_id' => $idOperadora]);
        }
    }

    public function giveConveniada(int $empresaId): void
    {
        $idConvenio = Convenio::where('empresa_id', $empresaId)->first()->id;

        if ($idConvenio) {
            $this->conveniadas()->firstOrCreate(['empresa_id' => $this->id, 'convenio_id' => $idConvenio]);
        }
    }
}
