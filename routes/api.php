<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\MyRoutineController;
use App\Http\Controllers\Api\RoutineController;
use Illuminate\Support\Facades\Route;

// Rutas públicas de autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas públicas
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/categories/{category}/exercises', [CategoryController::class, 'exercises']);

Route::get('/exercises', [ExerciseController::class, 'index']);
Route::get('/exercises/{exercise}', [ExerciseController::class, 'show']);

Route::get('/routines', [RoutineController::class, 'index']);
Route::get('/routines/{routine}', [RoutineController::class, 'show']);
Route::get('/routines/{routine}/exercises', [RoutineController::class, 'exercises']);

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    
    // Autenticación
    Route::post('/logout', [AuthController::class, 'logout']);

    // Categorías
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    // Ejercicios
    Route::post('/exercises', [ExerciseController::class, 'store']);
    Route::put('/exercises/{exercise}', [ExerciseController::class, 'update']);
    Route::delete('/exercises/{exercise}', [ExerciseController::class, 'destroy']);

    // Rutinas
    Route::post('/routines', [RoutineController::class, 'store']);
    Route::put('/routines/{routine}', [RoutineController::class, 'update']);
    Route::delete('/routines/{routine}', [RoutineController::class, 'destroy']);
    Route::post('/routines/{routine}/exercises', [RoutineController::class, 'addExercise']);
    Route::delete('/routines/{routine}/exercises/{exercise}', [RoutineController::class, 'removeExercise']);

    // Mis rutinas
    Route::get('/my-routines', [MyRoutineController::class, 'index']);
    Route::post('/my-routines', [MyRoutineController::class, 'store']);
    Route::delete('/my-routines/{routine}', [MyRoutineController::class, 'destroy']);
});