<?php

namespace App\Http\Controllers;

use App\Http\Resources\AnimalTypeResource;
use App\Models\AnimalType;

class AnimalTypeController extends Controller
{
    public function index()
    {
        return AnimalTypeResource::collection(AnimalType::all());
    }
}
