<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['role'];
    public function users(): BelongsToMany
    {
        return  $this->belongsToMany(User::class);
    }

    public function permissons(): BelongsToMany
    {
        return  $this->belongsToMany(Permission::class);
    }
}
