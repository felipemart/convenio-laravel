<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Operadora']);
        Role::create(['name' => 'Convenio']);
        Role::create(['name' => 'Conveniada']);
    }
}
