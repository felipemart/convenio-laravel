<?php

declare(strict_types = 1);

use App\Models\Permission;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

it('users method returns a BelongsToMany relation', function () {
    $permission = new Permission();
    expect($permission->users())->toBeInstanceOf(BelongsToMany::class);
});

it('roles method returns a BelongsToMany relation', function () {
    $permission = new Permission();
    expect($permission->roles())->toBeInstanceOf(BelongsToMany::class);
});
