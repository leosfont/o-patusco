<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return new UserResource(User::whereHas('roles', fn ($q) => $q->where('name', 'client'))->get());
    }
}
