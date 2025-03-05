<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conveniada extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['id' , 'convenio_id'];

    /**
     * Get the convenio that owns the conveniada.
     */
    public function convenio()
    {
        return $this->belongsTo(Convenio::class);
    }

    /**
     * Get the empresa that owns the conveniada.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }
}
