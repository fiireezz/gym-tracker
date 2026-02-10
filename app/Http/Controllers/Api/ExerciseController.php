<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::with('category')->get();
        return ExerciseResource::collection($exercises);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'instruction' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $exercise = Exercise::create($request->all());

        return new ExerciseResource($exercise->load('category'));
    }

    public function show(Exercise $exercise)
    {
        return new ExerciseResource($exercise->load('category'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $request->validate([
            'name' => 'required|string',
            'instruction' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $exercise->update($request->all());

        return new ExerciseResource($exercise->load('category'));
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return response()->json([
            'message' => 'Ejercicio eliminado correctamente',
        ]);
    }
}