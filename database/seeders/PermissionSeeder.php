<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['permission' => 'operadora.create', 'role_id' => 1]);
        Permission::create(['permission' => 'operadora.edit', 'role_id' => 1]);
        Permission::create(['permission' => 'operadora.delete', 'role_id' => 1]);
        Permission::create(['permission' => 'operadora.list', 'role_id' => 1]);
    }
}
