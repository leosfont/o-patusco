<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Appointment;
use App\Models\AnimalType;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesSeeder::class);

        $doctor = User::factory()->create([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com',
        ]);
        $doctor->assignRole('doctor');

        $receptionist = User::factory()->create([
            'name' => 'Receptionist User',
            'email' => 'receptionist@example.com',
        ]);
        $receptionist->assignRole('receptionist');

        AnimalType::factory()->count(3)->create();

        User::factory()->count(5)->create()->each(function ($user) use ($doctor) {
            Appointment::factory()->count(2)->create([
                'user_id' => $user->id,
                'doctor_id' => $doctor->id
            ]);
            $user->assignRole('client');
        });
    }
}
