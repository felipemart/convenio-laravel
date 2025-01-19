<?php

namespace Database\Seeders;

use App\Models\{Conveniada, Convenio, Empresa, Operadora, User};
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empresa = Empresa::factory()->create([
            'role_id' => 1,
        ]);
        User::factory()
            ->withPermissions('incluir')
            ->withRoles('Admin')
            ->create([
                'name'       => 'Admin',
                'email'      => 'admin@localhost.com',
                'password'   => bcrypt('123'),
                'empresa_id' => $empresa->id,
            ]);

        $operadora = Operadora::create();

        $empresa = Empresa::factory()->create([
            'operadora_id' => $operadora->id,
            'role_id'      => 2,
        ]);

        User::factory()
            ->count(3)
            ->withRoles('Operadora')
            ->create([
                'empresa_id' => $empresa->id,
            ]);
        User::factory()
            ->count(3)
            ->withRoles('Operadora')
            ->create([
                'empresa_id' => $empresa->id,
                'deleted_at' => now(),
            ]);
        $convenio = Convenio::create();

        $empresa = Empresa::factory()->create([
            'operadora_id' => $operadora->id,
            'convenio_id'  => $convenio->id,
            'role_id'      => 3,
        ]);
        User::factory()
            ->count(3)
            ->withRoles('Convenio')
            ->create([
                'empresa_id' => $empresa->id,
            ]);
        User::factory()
            ->count(3)
            ->withRoles('Convenio')
            ->create([
                'empresa_id' => $empresa->id,
                'deleted_at' => now(),
            ]);

        $conveniada = Conveniada::create();

        $empresa = Empresa::factory()->create([
            'operadora_id'  => $operadora->id,
            'convenio_id'   => $convenio->id,
            'conveniada_id' => $conveniada->id,
            'role_id'       => 4,
        ]);
        User::factory()
            ->count(3)
            ->withRoles('Conveniada')
            ->create([
                'empresa_id' => $empresa->id,
            ]);
        User::factory()
            ->count(3)
            ->withRoles('Conveniada')
            ->create([
                'empresa_id' => $empresa->id,
                'deleted_at' => now(),
            ]);

    }
}
