<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository extends Repository
{
    public function __construct(Appointment $appointment)
    {
        parent::__construct($appointment);
    }

    public function getQuery()
    {
        return Appointment::query();
    }

    public function getQueryByDoctorId($doctorId)
    {
        return Appointment::where('doctor_id', $doctorId);
    }

    public function getQueryByUserId($userId)
    {
        return Appointment::where('user_id', $userId);
    }
}
