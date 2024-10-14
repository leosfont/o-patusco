<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $doctor = Role::create(['name' => 'doctor']);
        $receptionist = Role::create(['name' => 'receptionist']);
        $client = Role::create(['name' => 'client']);

        Permission::create(['name' => 'view appointments']);
        Permission::create(['name' => 'create appointments']);
        Permission::create(['name' => 'edit appointments']);
        Permission::create(['name' => 'delete appointments']);
        Permission::create(['name' => 'assign doctor to appointments']);
        
        $doctor->givePermissionTo('view appointments');
        $doctor->givePermissionTo('edit appointments');

        $receptionist->givePermissionTo('view appointments');
        $receptionist->givePermissionTo('create appointments');
        $receptionist->givePermissionTo('edit appointments');
        $receptionist->givePermissionTo('delete appointments');
        $receptionist->givePermissionTo('assign doctor to appointments');

        $client->givePermissionTo('view appointments');
        $client->givePermissionTo('create appointments');
        $client->givePermissionTo('delete appointments');
    }
}
