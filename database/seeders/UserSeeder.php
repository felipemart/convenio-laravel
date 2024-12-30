<?php

namespace Database\Seeders;

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
            ->withRoles('admin')
            ->create([
                'name'     => 'Admin',
                'email'    => 'admin@localhost.com',
                'password' => bcrypt('123'),
            ]);

        User::factory()
            ->count(50)
            ->withRoles('test')
            ->create();
        User::factory()
            ->count(50)
            ->withRoles('test')
            ->create([
                'deleted_at' => now(),
            ]);
    }
}
