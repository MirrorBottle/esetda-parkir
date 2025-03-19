<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Biro extends Model
{
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function cars()
    {
        return $this->hasManyThrough(Car::class, Employee::class);
    }
}
