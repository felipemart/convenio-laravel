<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = ['permission'];
    public function users(): BelongsToMany
    {
        return  $this->belongsToMany(User::class);
    }

    public function roles(): BelongsToMany
    {
        return  $this->belongsToMany(Role::class);
    }
}
