<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    ];

    public function operadora()
    {
        return $this->hasMany(Operadora::class);
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }

    public function conveniadas()
    {
        return $this->hasMany(Conveniada::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function giveOperadora(Empresa $empresa): void
    {
        $this->operadora_id = $empresa->id;
        $this->save();
    }

    public function giveConvenio(Empresa $empresa): void
    {
        $this->convenio_id = $empresa->id;
        $this->save();
    }

    public function giveConveniada(Empresa $empresa): void
    {
        $this->conveniada_id = $empresa->id;
        $this->save();
    }
}
