<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRoutineRequest;
use App\Http\Resources\ExerciseResource;
use App\Http\Resources\RoutineResource;
use App\Models\Routine;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    public function index()
    {
        $routines = Routine::with('exercises')->get();
        return RoutineResource::collection($routines);
    }

    public function store(StoreRoutineRequest $request)
    {
        $routine = Routine::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $routine->users()->attach($request->user()->id);

        if ($request->has('exercises') && is_array($request->exercises)) {
            foreach ($request->exercises as $index => $exercise) {
                $routine->exercises()->attach($exercise['exercise_id'], [
                    'sequence' => $index + 1,
                    'target_sets' => $exercise['target_sets'] ?? 3,
                    'target_reps' => $exercise['target_reps'] ?? 10,
                    'rest_seconds' => $exercise['rest_seconds'] ?? 60,
                ]);
            }
        }

        return new RoutineResource($routine->load('exercises'));
    }

    public function show(Routine $routine)
    {
        return new RoutineResource($routine->load('exercises'));
    }

    public function update(Request $request, Routine $routine)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $routine->update($request->all());

        return new RoutineResource($routine->load('exercises'));
    }

    public function destroy(Routine $routine)
    {
        $routine->delete();

        return response()->json([
            'message' => 'Rutina eliminada correctamente',
        ]);
    }

    public function exercises(Routine $routine)
    {
        $exercises = $routine->exercises;
        return ExerciseResource::collection($exercises);
    }

    public function addExercise(Request $request, Routine $routine)
    {
        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'target_sets' => 'nullable|integer|min:1',
            'target_reps' => 'nullable|integer|min:1',
            'rest_seconds' => 'nullable|integer|min:0',
        ]);

        $maxSequence = $routine->exercises()->max('sequence') ?? 0;

        $routine->exercises()->attach($request->exercise_id, [
            'sequence' => $maxSequence + 1,
            'target_sets' => $request->target_sets ?? 3,
            'target_reps' => $request->target_reps ?? 10,
            'rest_seconds' => $request->rest_seconds ?? 60,
        ]);

        return new RoutineResource($routine->load('exercises'));
    }

    public function removeExercise(Routine $routine, $exerciseId)
    {
        $routine->exercises()->detach($exerciseId);

        return response()->json([
            'message' => 'Ejercicio eliminado de la rutina',
        ]);
    }
}