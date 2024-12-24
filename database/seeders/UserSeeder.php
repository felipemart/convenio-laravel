<?php

namespace Database\Seeders;

use App\Enum\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::factory()
            ->withPermissions('incluir')
            ->withRoles(RoleEnum::ADMIN->value)
            ->create([
                'name'     => 'Admin',
                'email'    => 'admin@localhost.com',
                'password' => bcrypt('123'),
            ]);
    }
}
