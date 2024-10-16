<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

class DoctorController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::whereHas('roles', fn ($q) => $q->where('name', 'doctor'))->get());
    }
}
