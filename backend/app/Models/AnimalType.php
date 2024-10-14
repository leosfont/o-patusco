<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalType extends Model
{
    use HasFactory;

    protected $fillable = ['type_name'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'animal_type_id');
    }
}
