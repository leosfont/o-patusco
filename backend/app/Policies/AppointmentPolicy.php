<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AppointmentPolicy
{
    use HandlesAuthorization;

    public function create(User $user, Appointment $appointment)
    {
        if ($user->hasRole('client')) {
            return $user->id === $appointment->client;
        }

        return false;
    }

    public function update(User $user, Appointment $appointment)
    {
        if ($user->hasRole('client')) {
            return $user->id === $appointment->client;
        }

        if ($user->hasRole('doctor')) {
            return $user->id === $appointment->doctor_id;
        }

        return false;
    }
}
