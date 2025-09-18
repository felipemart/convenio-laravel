<?php

declare(strict_types = 1);

namespace App\Models;

use Database\Factories\RegrasAssociadaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegrasAssociada extends Model
{
    /** @use HasFactory<RegrasAssociadaFactory> */
    use HasFactory;

    protected $fillable = [
        'regra_id',
        'produto_id',
        'classificacao_id',
    ];

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function regra(): BelongsTo
    {
        return $this->belongsTo(Regra::class, 'regra_id');
    }

    public function classificacao(): BelongsTo
    {
        return $this->belongsTo(ClassificacaoProduto::class, 'classificacao_id');
    }
}
