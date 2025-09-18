<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GrupoDesconto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "grupo_descontos";

    protected $fillable = [
        'descricao',
        'ativo',
        'operadora_id',
    ];

    public function operadoras(): BelongsTo
    {
        return $this->belongsTo(Operadora::class, 'operadora_id');
    }

    public function regras(): HasMany
    {
        return $this->hasMany(Regra::class, 'grupo_desconto_id');
    }
}
