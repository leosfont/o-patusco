<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'animal_name',
        'animal_type_id',
        'animal_age',
        'symptoms',
        'appointment_date',
        'appointment_time',
        'doctor_id',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function animalType()
    {
        return $this->belongsTo(AnimalType::class, 'animal_type_id');
    }
}
