<?php

declare(strict_types = 1);

namespace Database\Seeders;

use App\Models\Conveniada;
use App\Models\Convenio;
use App\Models\Empresa;
use App\Models\Operadora;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //admin
        User::factory()
            ->withPermissions('incluir')
            ->withRoles('Admin')
            ->create([
                'name'     => 'Admin',
                'email'    => 'admin@localhost.com',
                'password' => bcrypt('123'),
            ]);

        for ($i = 0; $i < 4; $i++) {
            $empresaOperadora = Empresa::factory()->create([
                'role_id' => 2,
            ]);
            $operadora = Operadora::create([
                'empresa_id' => $empresaOperadora->id,
            ]);

            $empresaOperadora->giveOperadora($empresaOperadora);

            User::factory()
                ->count(3)
                ->withRoles('Operadora')
                ->create([
                    'empresa_id' => $empresaOperadora->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Operadora')
                ->create([
                    'empresa_id' => $empresaOperadora->id,
                    'deleted_at' => now(),
                ]);

            $empresaConvenio = Empresa::factory()->create([
                'role_id' => 3,
            ]);
            $convenio = Convenio::create([
                'empresa_id' => $empresaConvenio->id,
            ]);

            $empresaConvenio->giveOperadora($empresaOperadora);
            $empresaConvenio->giveConvenio($empresaConvenio);

            User::factory()
                ->count(3)
                ->withRoles('Convenio')
                ->create([
                    'empresa_id' => $empresaConvenio->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Convenio')
                ->create([
                    'empresa_id' => $empresaConvenio->id,
                    'deleted_at' => now(),
                ]);

            //conveniada 1

            $empresaConveniada = Empresa::factory()->create([
                'role_id' => 4,
            ]);
            $conveniada = Conveniada::create([
                'empresa_id' => $empresaConveniada->id,
            ]);
            $empresaConveniada->giveOperadora($empresaOperadora);
            $empresaConveniada->giveConvenio($empresaConvenio);
            $empresaConveniada->giveConveniada($empresaConveniada);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada->id,
                    'deleted_at' => now(),
                ]);

            //conveniada 2
            $empresaConveniada2 = Empresa::factory()->create([
                'role_id' => 4,
            ]);
            $conveniada2 = Conveniada::create([
                'empresa_id' => $empresaConveniada2->id,
            ]);

            $empresaConveniada2->giveOperadora($empresaOperadora);
            $empresaConveniada2->giveConvenio($empresaConvenio);
            $empresaConveniada2->giveConveniada($empresaConveniada2);

            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada2->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada2->id,
                    'deleted_at' => now(),
                ]);

            //convenio2
            $empresaConvenio2 = Empresa::factory()->create([
                'role_id' => 3,
            ]);

            $convenio = Convenio::create([
                'empresa_id' => $empresaConvenio2->id,
            ]);
            $empresaConvenio2->giveOperadora($empresaOperadora);
            $empresaConvenio2->giveConvenio($empresaConvenio2);

            User::factory()
                ->count(3)
                ->withRoles('Convenio')
                ->create([
                    'empresa_id' => $empresaConvenio2->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Convenio')
                ->create([
                    'empresa_id' => $empresaConvenio2->id,
                    'deleted_at' => now(),
                ]);

            //conveniada
            $empresaConveniada3 = Empresa::factory()->create([
                'role_id' => 4,
            ]);
            $conveniada3 = Conveniada::create([
                'empresa_id' => $empresaConveniada3->id,
            ]);

            $empresaConveniada3->giveOperadora($empresaOperadora);
            $empresaConveniada3->giveConvenio($empresaConvenio2);
            $empresaConveniada3->giveConveniada($empresaConveniada3);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada3->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada3->id,
                    'deleted_at' => now(),
                ]);

            //conveniada 2

            $empresaConveniada4 = Empresa::factory()->create([
                'role_id' => 4,
            ]);
            $conveniada4 = Conveniada::create([
                'empresa_id' => $empresaConveniada4->id,
            ]);
            $empresaConveniada4->giveOperadora($empresaOperadora);
            $empresaConveniada4->giveConvenio($empresaConvenio2);
            $empresaConveniada4->giveConveniada($empresaConveniada4);

            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada4->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresaConveniada4->id,
                    'deleted_at' => now(),
                ]);
        }
    }
}
