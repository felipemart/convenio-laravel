<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operadora extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id'];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'id');
    }

    public function convenios()
    {
        return $this->hasMany(Convenio::class);
    }
}
