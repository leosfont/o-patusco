<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Services\UserService;

class AppointmentService extends Service
{
    protected $userService;

    public function __construct(AppointmentRepository $appointmentRepository, UserService $userService)
    {
        parent::__construct($appointmentRepository);
        $this->userService = $userService;
    }

    public function createAndRegisterUser(array $userData, array $appointmentData)
    {
        $user = $this->userService->create($userData);

        $appointmentData['user_id'] = $user->id;

        return $this->repository->create($appointmentData);
    }
}
