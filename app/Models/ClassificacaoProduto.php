<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassificacaoProduto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'classificacao_produtos';

    protected $fillable = [
        'descricao',
        'tipo',
        'codigo',
    ];

    public function getTipoTexto(): string
    {
        return match ($this->tipo) {
            1       => 'Departamento',
            2       => 'Classe Terapêutica',
            3       => 'Laboratório',
            4       => 'Princípio Ativo',
            default => 'Desconhecido',
        };
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'classificacao_produto_produto');
    }
}
