<?php

namespace Database\Factories;

use App\Models\Editor;
use Illuminate\Database\Eloquent\Factories\Factory;

class EditorFactory extends Factory
{
    protected $model = Editor ::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->company(),
            'logotipo' => null,
        ];
    }
}