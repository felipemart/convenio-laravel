<?php

declare(strict_types = 1);

namespace App\Models;

use Database\Factories\ProdutoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    /** @use HasFactory<ProdutoFactory> */
    use HasFactory;
    use HasFactory;
    use SoftDeletes;

    protected $table = 'produtos';

    protected $fillable = [
        'descricao',
        'ean_id',
        'codigo',
        'pr_maximo',
    ];

    public function ClassificacaoProdutos(): BelongsToMany
    {
        return $this->belongsToMany(ClassificacaoProduto::class, 'classificacao_produto_produto', 'produto_id', 'classificacao_produto_id');
    }

    public function Operadoras(): BelongsTo
    {
        return $this->belongsTo(Operadora::class, 'operadora_id');
    }
}
