<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoutineResource;
use App\Models\Routine;
use Illuminate\Http\Request;

class MyRoutineController extends Controller
{
    public function index(Request $request)
    {
        $routines = $request->user()->routines()->with('exercises')->get();
        return RoutineResource::collection($routines);
    }

    public function store(Request $request)
    {
        $request->validate([
            'routine_id' => 'required|exists:routines,id',
        ]);

        $user = $request->user();

        if ($user->routines()->where('routine_id', $request->routine_id)->exists()) {
            return response()->json([
                'message' => 'Ya estás suscrito a esta rutina',
            ], 400);
        }

        $user->routines()->attach($request->routine_id);

        return response()->json([
            'message' => 'Suscrito a la rutina correctamente',
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->routines()->detach($id);

        return response()->json([
            'message' => 'Desuscrito de la rutina correctamente',
        ]);
    }
}