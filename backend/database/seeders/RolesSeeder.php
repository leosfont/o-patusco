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

        Permission::create(['name' => 'appointments.index']);
        Permission::create(['name' => 'appointments.store']);
        Permission::create(['name' => 'appointments.update']);
        Permission::create(['name' => 'appointments.destroy']);
        Permission::create(['name' => 'users.index']);
        Permission::create(['name' => 'doctors.index']);

        $doctor->givePermissionTo('appointments.index');
        $doctor->givePermissionTo('appointments.update');

        $receptionist->givePermissionTo('appointments.index');
        $receptionist->givePermissionTo('appointments.store');
        $receptionist->givePermissionTo('appointments.update');
        $receptionist->givePermissionTo('appointments.destroy');

        $client->givePermissionTo('appointments.index');
        $client->givePermissionTo('appointments.store');
    }
}
