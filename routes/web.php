<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Gym Tracker API',
        'version' => '1.0',
        'endpoints' => [
            'categories' => '/api/categories',
            'exercises' => '/api/exercises',
            'routines' => '/api/routines',
            'register' => 'POST /api/register',
            'login' => 'POST /api/login',
        ]
    ]);
});