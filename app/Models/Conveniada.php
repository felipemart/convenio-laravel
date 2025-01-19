<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};

class Conveniada extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id'];
    public function empresas(): HasOne
    {
        return $this->hasOne(Empresa::class);
    }
}
