<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'instruction' => $this->instruction,
            'category_id' => $this->category_id,
        ];

        if ($this->relationLoaded('category')) {
            $data['category'] = new CategoryResource($this->category);
        }

        if ($this->pivot) {
            $data['sequence'] = $this->pivot->sequence;
            $data['target_sets'] = $this->pivot->target_sets;
            $data['target_reps'] = $this->pivot->target_reps;
            $data['rest_seconds'] = $this->pivot->rest_seconds;
        }

        return $data;
    }
}