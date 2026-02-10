<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoutineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'exercises' => 'required|array|min:1',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.target_sets' => 'nullable|integer|min:1',
            'exercises.*.target_reps' => 'nullable|integer|min:1',
            'exercises.*.rest_seconds' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la rutina es obligatorio',
            'exercises.required' => 'Debes incluir al menos un ejercicio',
            'exercises.min' => 'Debes incluir al menos un ejercicio',
            'exercises.*.exercise_id.exists' => 'Uno o más ejercicios no existen',
        ];
    }
}