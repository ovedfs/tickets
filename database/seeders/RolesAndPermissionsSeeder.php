<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $arrendador = Role::create(['name' => 'arrendador']);
        $abogado = Role::create(['name' => 'abogado']);

        Permission::create(['name' => 'tickets.index'])->syncRoles([$arrendador, $abogado]);
        Permission::create(['name' => 'tickets.store'])->syncRoles([$arrendador]);
        Permission::create(['name' => 'tickets.show'])->syncRoles([$arrendador, $abogado]);
        Permission::create(['name' => 'tickets.update'])->syncRoles([$arrendador, $abogado]);
        Permission::create(['name' => 'tickets.close'])->syncRoles([$arrendador, $abogado]);
        Permission::create(['name' => 'tickets.destroy'])->syncRoles([$abogado]);
    }
}
