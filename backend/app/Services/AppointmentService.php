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

    public function getAppointmentsByRoleAuthUser()
    {
        $user = auth()->user()->load('roles');
        $query = null;
    
        if ($user->hasRole('recepcionist')) {
            $query = $this->repository->getQuery();
        } elseif ($user->hasRole('doctor')) {
            $query = $this->repository->getQueryByDoctorId($user->id);
        } else {
            $query = $this->repository->getQueryByUserId($user->id);
        }

        $query = $this->repository->getQuery();
    
        $query->with('user', 'doctor', 'animalType');
    
        if (request()->has('appointment_date')) {
            $query->whereDate('appointment_date', request('appointment_date'));
        }
    
        if (request()->has('animal_type_id')) {
            $query->where('animal_type_id', request('animal_type_id'));
        }
    
        $appointments = $query->paginate(10); 
        return $appointments;
    }
    

    public function createAndRegisterUser(array $userData, array $appointmentData)
    {
        $user = $this->userService->create($userData);

        $appointmentData['user_id'] = $user->id;

        return $this->repository->create($appointmentData);
    }
}
