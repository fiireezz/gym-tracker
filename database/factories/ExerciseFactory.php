<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExerciseFactory extends Factory
{
    protected $model = Exercise::class;

    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'instruction' => $this->faker->paragraph(),
        ];
    }
}