<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

class Role extends Model
{
    protected $fillable = ['name'];
    public function users(): HasMany
    {
        return  $this->hasMany(User::class);
    }

    public function permissons(): BelongsToMany
    {
        return  $this->belongsToMany(Permission::class);
    }
}
