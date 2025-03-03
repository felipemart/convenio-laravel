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
    public function run(): void
    {
        // Admin
        User::factory()
            ->withPermissions('incluir')
            ->withRoles('Admin')
            ->create([
                'name'     => 'Admin',
                'email'    => 'admin@localhost.com',
                'password' => bcrypt('123'),
            ]);

        for ($i = 0; $i < 4; $i++) {
            // Empresa (Operadora)
            $empresaOperadora = Empresa::factory()->create();
            $operadora        = Operadora::create(['empresa_id' => $empresaOperadora->id]);

            // Usuários da Operadora
            User::factory(3)->withRoles('Operadora')->create(['empresa_id' => $empresaOperadora->id]);
            User::factory(3)->withRoles('Operadora')->create(['empresa_id' => $empresaOperadora->id, 'deleted_at' => now()]); // Usuários deletados

            // Empresa (Convênio)
            $empresaConvenio = Empresa::factory()->create();
            $convenio        = Convenio::create([
                'operadora_id' => $operadora->id,
                'empresa_id'   => $empresaConvenio->id,
            ]);

            // Usuários do Convênio
            User::factory(3)->withRoles('Convenio')->create(['empresa_id' => $empresaConvenio->id]);
            User::factory(3)->withRoles('Convenio')->create(['empresa_id' => $empresaConvenio->id, 'deleted_at' => now()]); // Usuários deletados

            // Empresas (Conveniadas)
            $empresaConveniada1 = Empresa::factory()->create();
            $conveniada1        = Conveniada::create([
                'convenio_id' => $convenio->id,
                'empresa_id'  => $empresaConveniada1->id,
            ]);

            // Usuários da Conveniada 1
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada1->id]);
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada1->id, 'deleted_at' => now()]); // Usuários deletados

            $empresaConveniada2 = Empresa::factory()->create();
            $conveniada2        = Conveniada::create([
                'convenio_id' => $convenio->id,
                'empresa_id'  => $empresaConveniada2->id,
            ]);

            // Usuários da Conveniada 2
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada2->id]);
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada2->id, 'deleted_at' => now()]); // Usuários deletados

            // Outra Empresa (Convênio) - Mesmo Operadora
            $empresaConvenio2 = Empresa::factory()->create();
            $convenio2        = Convenio::create([
                'operadora_id' => $operadora->id,
                'empresa_id'   => $empresaConvenio2->id,
            ]);

            // Usuários do Convênio 2
            User::factory(3)->withRoles('Convenio')->create(['empresa_id' => $empresaConvenio2->id]);
            User::factory(3)->withRoles('Convenio')->create(['empresa_id' => $empresaConvenio2->id, 'deleted_at' => now()]); // Usuários deletados

            // Outras Empresas (Conveniadas) - Mesmo Convênio 2
            $empresaConveniada3 = Empresa::factory()->create();
            $conveniada3        = Conveniada::create([
                'convenio_id' => $convenio2->id,
                'empresa_id'  => $empresaConveniada3->id,
            ]);

            // Usuários da Conveniada 3
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada3->id]);
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada3->id, 'deleted_at' => now()]); // Usuários deletados

            $empresaConveniada4 = Empresa::factory()->create();
            $conveniada4        = Conveniada::create([
                'convenio_id' => $convenio2->id,
                'empresa_id'  => $empresaConveniada4->id,
            ]);

            // Usuários da Conveniada 4
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada4->id]);
            User::factory(3)->withRoles('Conveniada')->create(['empresa_id' => $empresaConveniada4->id, 'deleted_at' => now()]); // Usuários deletados
        }

        // Usuários operadora
        User::factory()
            ->withPermissions('incluir')
            ->withRoles('operadora')
            ->create([
                'name'       => 'Operadora',
                'email'      => 'operadora@localhost.com',
                'empresa_id' => 2,
                'password'   => bcrypt('123'),
            ]);
        // Usuários Convenio
        User::factory()
            ->withPermissions('incluir')
            ->withRoles('convenio')
            ->create([
                'name'       => 'Convenio',
                'email'      => 'convenio@localhost.com',
                'empresa_id' => 3,
                'password'   => bcrypt('123'),
            ]);

        // Usuários Conveniada
        User::factory()
            ->withPermissions('incluir')
            ->withRoles('conveniada')
            ->create([
                'name'       => 'Conveniada',
                'email'      => 'conveniada@localhost.com',
                'empresa_id' => 4,
                'password'   => bcrypt('123'),
            ]);
    }
}
