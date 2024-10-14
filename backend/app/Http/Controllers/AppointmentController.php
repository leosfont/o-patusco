<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Http\Requests\StoreAndRegisterUserRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use App\Models\Appointment;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    protected $appointmentService;
    protected $userService;

    public function __construct(AppointmentService $appointmentService, UserService $userService)
    {
        $this->appointmentService = $appointmentService;
        $this->userService = $userService;
    }

    public function index()
    {
        $appointments = $this->appointmentService->getAll();
        return AppointmentResource::collection($appointments);
    }


    public function storeAndRegisterUser(StoreAndRegisterUserRequest $request)
    {
        $fields = $request->validated();

        $userData = [
            'name' => $fields['name'],
            'email' => $fields['email'],
        ];

        $appointmentData = [
            'animal_name' => $fields['animal_name'],
            'animal_type_id' => $fields['animal_type_id'],
            'animal_age' => $fields['animal_age'],
            'symptoms' => $fields['symptoms'],
            'appointment_date' => $fields['appointment_date'],
            'appointment_time' => $fields['appointment_time'],
        ];

        try {
            DB::beginTransaction();
    
            $user = $this->userService->create($userData);
    
            $appointmentData['user_id'] = $user->id;
    
            $appointment = $this->appointmentService->create($appointmentData);
    
            DB::commit();
            return new AppointmentResource($appointment);
    
        } catch (\Exception $e) {
            DB::rollBack();
    
            return response()->json([
                'message' => 'Failed to create user and appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->create($request->validated());
        return new AppointmentResource($appointment);
    }

    public function show($id)
    {
        $appointment = $this->appointmentService->findById($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        return new AppointmentResource($appointment);
    }

    public function update(AppointmentUpdateRequest $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        $updatedAppointment = $this->appointmentService->update($appointment, $request->validated());
        return new AppointmentResource($updatedAppointment);
    }


    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        $this->appointmentService->delete($appointment);
        return response()->json(['message' => 'Appointment deleted successfully']);
    }
}
