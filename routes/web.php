<?php

use App\Http\Controllers\DebtController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::resource('teachers', TeacherController::class );
Route::resource('students', StudentController::class );
Route::resource('groups', GroupController::class );
Route::resource('debts', DebtController::class );
Route::resource('payments', PaymentController::class );



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
