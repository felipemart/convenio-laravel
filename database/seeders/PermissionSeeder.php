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
        Permission::create(['permission' => 'operadora.create', 'role_id' => 1, 'descricao' => 'Cadastro de Operadoras']);
        Permission::create(['permission' => 'operadora.edit', 'role_id' => 1, 'descricao' => 'Edição de Operadoras']);
        Permission::create(['permission' => 'operadora.delete', 'role_id' => 1, 'descricao' => 'Exclusão de Operadoras']);
        Permission::create(['permission' => 'operadora.list', 'role_id' => 1, 'descricao' => 'Listagem de Operadoras']);

        Permission::create(['permission' => 'convenio.create', 'role_id' => 2, 'descricao' => 'Cadastro de Convenios']);
        Permission::create(['permission' => 'convenio.edit', 'role_id' => 2, 'descricao' => 'Edição de Convenios']);
        Permission::create(['permission' => 'convenio.delete', 'role_id' => 2,  'descricao' => 'Exclusão de Convenios']);
        Permission::create(['permission' => 'convenio.list', 'role_id' => 2, 'descricao' => 'Listagem de Convenios']);

        Permission::create(['permission' => 'conveniada.create', 'role_id' => 3, 'descricao' => 'Cadastro de Conveniadas']);
        Permission::create(['permission' => 'conveniada.edit', 'role_id' => 3, 'descricao' => 'Edição de Conveniadas']);
        Permission::create(['permission' => 'conveniada.delete', 'role_id' => 3, 'descricao' => 'Exclusão de Conveniadas']);
        Permission::create(['permission' => 'conveniada.list', 'role_id' => 3, 'descricao' => 'Listagem de Conveniadas']);

        Permission::create(['permission' => 'usuario.create', 'role_id' => 3, 'descricao' => 'Cadastro de Usuários']);
        Permission::create(['permission' => 'usuario.edit', 'role_id' => 3, 'descricao' => 'Edição de Usuários']);
        Permission::create(['permission' => 'usuario.delete', 'role_id' => 3, 'descricao' => 'Exclusão de Usuários']);
        Permission::create(['permission' => 'usuario.list', 'role_id' => 3, 'descricao' => 'Listagem de Usuários']);
        Permission::create(['permission' => 'usuario.permission', 'role_id' => 3, 'descricao' => 'Permisão de Usuários']);
    }
}
