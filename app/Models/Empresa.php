<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

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

    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function operadora(): BelongsTo
    {
        return $this->belongsTo(Operadora::class);

    }

    public function convenio(): BelongsTo
    {
        return $this->belongsTo(Convenio::class);

    }
    public function conveniada(): BelongsTo
    {
        return $this->belongsTo(Conveniada::class);

    }

    public function giveOperadora(Operadora $operadora): void
    {
        $this->operadora_id = $operadora->id;
        $this->save();

    }

    public function giveConvenio(Convenio $convenio): void
    {

        $this->convenio_id = $convenio->id;
        $this->save();
    }

    public function giveConveniada(Conveniada $conveniada): void
    {
        $this->conveniada_id = $conveniada->id;
        $this->save();
    }

}
