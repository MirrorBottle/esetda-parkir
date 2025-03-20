<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function show(string $uuid)
    {
        $data = Car::where('uuid', $uuid)->firstOrFail();
        return view('cars.show', compact('data'));
    }
}
