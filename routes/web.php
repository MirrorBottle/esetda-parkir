<?php

use App\Http\Controllers\CarController;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/admin/login');
});
Route::get('/login', function () {
    return redirect('/admin/login');
});

Route::get('/detail-kendaraan/{uuid}', [CarController::class, 'show'])
    ->name('cars.show')
    ->middleware(Authenticate::class);

Route::get('/logged-in', function () {
    return view('logged-in');
})->middleware(Authenticate::class)->name('logged-in');