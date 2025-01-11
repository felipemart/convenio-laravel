<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Operadora extends Model
{
    use HasFactory;

    protected $fillable = ['id'];
    public function empresas(): HasOne
    {
        return $this->hasOne(Empresa::class);
    }
}
