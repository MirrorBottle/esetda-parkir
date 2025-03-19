<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function biro()
    {
        return $this->belongsTo(Biro::class);
    }
}
