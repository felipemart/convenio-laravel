<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['role' => RoleEnum::ADMIN->value]);
    }
}
