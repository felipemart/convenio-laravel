<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regra extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'grupo_desconto_id',
        'permite_venda',
        'descrento_com_crm',
        'desconto_sem_crm',
        'subsidio_com_crm',
        'subsidio_sem_crm',
        'validade_receita',
        'crm',
        'receita',
        'ativo',
        'tipo',
        'dt_inicio',
        'dt_fim',
    ];

    protected $casts = [
        'dt_inicio' => 'datetime',
        'dt_fim'    => 'datetime',
    ];

    public function grupo_descontos(): BelongsTo
    {
        return $this->belongsTo(GrupoDesconto::class, 'grupo_desconto_id');
    }
}
