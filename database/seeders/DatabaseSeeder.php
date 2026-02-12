<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Exercise;
use App\Models\Routine;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios de prueba
        $users = User::factory(5)->create();

        // Crear categorías específicas
        $categories = [
            ['name' => 'Pecho', 'icon_path' => 'icons/chest.svg'],
            ['name' => 'Espalda', 'icon_path' => 'icons/back.svg'],
            ['name' => 'Pierna', 'icon_path' => 'icons/legs.svg'],
            ['name' => 'Hombro', 'icon_path' => 'icons/shoulder.svg'],
            ['name' => 'Brazos', 'icon_path' => 'icons/arms.svg'],
            ['name' => 'Cardio', 'icon_path' => 'icons/cardio.svg'],
        ];

        $createdCategories = [];
        foreach ($categories as $category) {
            $createdCategories[] = Category::create($category);
        }

        // Crear ejercicios para cada categoría
        $exercisesByCategory = [
            'Pecho' => [
                ['name' => 'Press banca', 'instruction' => 'Acostado en banco, baja la barra al pecho y empuja hacia arriba'],
                ['name' => 'Flexiones', 'instruction' => 'En posición de plancha, baja el cuerpo hasta casi tocar el suelo'],
                ['name' => 'Aperturas con mancuernas', 'instruction' => 'Acostado, abre los brazos con mancuernas y vuelve'],
            ],
            'Espalda' => [
                ['name' => 'Dominadas', 'instruction' => 'Cuelga de la barra y sube hasta que la barbilla pase la barra'],
                ['name' => 'Remo con barra', 'instruction' => 'Inclinado, tira de la barra hacia el abdomen'],
                ['name' => 'Peso muerto', 'instruction' => 'Levanta la barra desde el suelo manteniendo la espalda recta'],
            ],
            'Pierna' => [
                ['name' => 'Sentadillas', 'instruction' => 'Baja como si te sentaras, mantén la espalda recta'],
                ['name' => 'Prensa', 'instruction' => 'En máquina, empuja el peso con los pies'],
                ['name' => 'Zancadas', 'instruction' => 'Da un paso largo y baja la rodilla trasera'],
            ],
            'Hombro' => [
                ['name' => 'Press militar', 'instruction' => 'De pie, empuja la barra desde los hombros hacia arriba'],
                ['name' => 'Elevaciones laterales', 'instruction' => 'Levanta las mancuernas a los lados hasta altura de hombros'],
            ],
            'Brazos' => [
                ['name' => 'Curl de bíceps', 'instruction' => 'Flexiona los codos llevando las mancuernas hacia los hombros'],
                ['name' => 'Fondos en paralelas', 'instruction' => 'Baja el cuerpo flexionando los codos'],
            ],
            'Cardio' => [
                ['name' => 'Correr', 'instruction' => 'Mantén un ritmo constante durante el tiempo establecido'],
                ['name' => 'Burpees', 'instruction' => 'Flexión, salto y aplauso en secuencia'],
            ],
        ];

        $allExercises = [];
        foreach ($createdCategories as $category) {
            if (isset($exercisesByCategory[$category->name])) {
                foreach ($exercisesByCategory[$category->name] as $exerciseData) {
                    $allExercises[] = Exercise::create([
                        'category_id' => $category->id,
                        'name' => $exerciseData['name'],
                        'instruction' => $exerciseData['instruction'],
                    ]);
                }
            }
        }

        // Crear rutinas y asignarlas a usuarios con ejercicios
        $routineTemplates = [
            [
                'name' => 'Rutina de Pecho y Tríceps',
                'description' => 'Entrenamiento enfocado en pecho y tríceps',
                'exercises' => ['Press banca', 'Flexiones', 'Aperturas con mancuernas', 'Fondos en paralelas'],
            ],
            [
                'name' => 'Rutina de Espalda y Bíceps',
                'description' => 'Entrenamiento para espalda y bíceps',
                'exercises' => ['Dominadas', 'Remo con barra', 'Peso muerto', 'Curl de bíceps'],
            ],
            [
                'name' => 'Rutina de Pierna',
                'description' => 'Día completo de pierna',
                'exercises' => ['Sentadillas', 'Prensa', 'Zancadas', 'Peso muerto'],
            ],
            [
                'name' => 'Rutina Full Body',
                'description' => 'Entrenamiento de cuerpo completo',
                'exercises' => ['Sentadillas', 'Press banca', 'Dominadas', 'Press militar'],
            ],
        ];

        foreach ($routineTemplates as $template) {
            $routine = Routine::create([
                'name' => $template['name'],
                'description' => $template['description'],
            ]);

            // Asignar a usuarios aleatorios
            $randomUsers = $users->random(rand(2, 4));
            $routine->users()->attach($randomUsers->pluck('id'));

            // Asignar ejercicios con datos de pivot
            foreach ($template['exercises'] as $index => $exerciseName) {
                $exercise = collect($allExercises)->firstWhere('name', $exerciseName);
                if ($exercise) {
                    $routine->exercises()->attach($exercise->id, [
                        'sequence' => $index + 1,
                        'target_sets' => rand(3, 5),
                        'target_reps' => rand(8, 12),
                        'rest_seconds' => rand(45, 90),
                    ]);
                }
            }
        }
    }
}