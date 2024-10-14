<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository extends Repository
{
    public function __construct(Appointment $appointment)
    {
        parent::__construct($appointment);
    }
}
