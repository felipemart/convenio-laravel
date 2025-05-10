<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'descricao',
        'codigo',
        'preco',
    ];

    public function classificacaoProduto()
    {
        return $this->belongsToMany(ClassificacaoProduto::class, 'classificacao_produto_produto', 'produto_id', 'classificacao_produto_id');
    }
}
