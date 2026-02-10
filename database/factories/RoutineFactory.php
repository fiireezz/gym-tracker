<?php

namespace Database\Factories;

use App\Models\Routine;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoutineFactory extends Factory
{
    protected $model = Routine::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
        ];
    }
}