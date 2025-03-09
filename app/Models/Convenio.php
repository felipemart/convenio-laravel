<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Convenio extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id', 'operadora_id'];

    /**
     * Get the operadora that owns the convenio.
     */
    public function operadora(): BelongsTo
    {
        return $this->belongsTo(Operadora::class);
    }

    /**
     * Get the empresa that owns the convenio.
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Get all of the conveniadas for the convenio.
     */
    public function conveniadas(): HasMany
    {
        return $this->hasMany(Conveniada::class);
    }
}
