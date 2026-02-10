<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Routine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'routine_user');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_routine')
            ->withPivot('sequence', 'target_sets', 'target_reps', 'rest_seconds')
            ->withTimestamps()
            ->orderBy('sequence');
    }
}

