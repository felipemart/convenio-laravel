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
        //admin
        User::factory()
            ->withPermissions('incluir')
            ->withRoles('Admin')
            ->create([
                'name'     => 'Admin',
                'email'    => 'admin@localhost.com',
                'password' => bcrypt('123'),
            ]);

        for ($i = 0; $i < 2; $i++) {
            $empresa = Empresa::factory()->create([
                'role_id' => 2,
            ]);
            $operadora = Operadora::create();

            $empresa->giveOperadora($operadora);

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

            $empresa = Empresa::factory()->create([
                'role_id' => 3,
            ]);
            $convenio = Convenio::create();

            $empresa->giveOperadora($operadora);
            $empresa->giveConvenio($convenio);

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

            //conveniada

            $empresa = Empresa::factory()->create([
                'role_id' => 4,
            ]);
            $conveniada = Conveniada::create();
            $empresa->giveOperadora($operadora);
            $empresa->giveConvenio($convenio);
            $empresa->giveConveniada($conveniada);
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

            //conveniada 2
            $conveniada2 = Conveniada::create();
            $empresa2    = Empresa::factory()->create([
                'role_id' => 4,
            ]);

            $empresa2->giveOperadora($operadora);
            $empresa2->giveConvenio($convenio);
            $empresa2->giveConveniada($conveniada2);

            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresa2->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresa2->id,
                    'deleted_at' => now(),
                ]);

            //convenio2
            $convenio3 = Convenio::create();
            $empresa3  = Empresa::factory()->create([
                'role_id' => 3,
            ]);
            $empresa3->giveOperadora($operadora);
            $empresa3->giveConvenio($convenio3);

            User::factory()
                ->count(3)
                ->withRoles('Convenio')
                ->create([
                    'empresa_id' => $empresa3->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Convenio')
                ->create([
                    'empresa_id' => $empresa3->id,
                    'deleted_at' => now(),
                ]);

            //conveniada
            $conveniada3 = Conveniada::create();

            $empresa3 = Empresa::factory()->create([
                'role_id' => 4,
            ]);
            $empresa3->giveOperadora($operadora);
            $empresa3->giveConvenio($convenio3);
            $empresa3->giveConveniada($conveniada3);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresa3->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresa3->id,
                    'deleted_at' => now(),
                ]);

            //conveniada 2
            $conveniada4 = Conveniada::create();
            $empresa4    = Empresa::factory()->create([
                'role_id' => 4,
            ]);

            $empresa4->giveOperadora($operadora);
            $empresa4->giveConvenio($convenio3);
            $empresa4->giveConveniada($conveniada4);

            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresa4->id,
                ]);
            User::factory()
                ->count(3)
                ->withRoles('Conveniada')
                ->create([
                    'empresa_id' => $empresa4->id,
                    'deleted_at' => now(),
                ]);

            $empresaTudo = Empresa::factory()->create([
                'role_id' => 2,
            ]);
            $operadoraTudo  = Operadora::create();
            $convenioTudo   = Convenio::create();
            $conveniadaTudo = Conveniada::create();

        }

    }
}
