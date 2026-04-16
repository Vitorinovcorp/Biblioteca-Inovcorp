<?php

namespace Database\Factories;

use App\Models\Livro;
use App\Models\Editor;
use Illuminate\Database\Eloquent\Factories\Factory;

class LivroFactory extends Factory
{
    protected $model = Livro::class;

    public function definition(): array
    {
        return [
            'isbn' => $this->faker->isbn13(),
            'nome' => $this->faker->sentence(3),
            'bibliografia' => $this->faker->paragraph(),
            'imagem_capa' => null,
            'preco' => $this->faker->randomFloat(2, 10, 100),
            'editora_id' => Editor::factory(),
            'external_id' => null,
            'quantidade' => $this->faker->numberBetween(0, 50),
            'user_id' => null,
        ];
    }

    public function semStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantidade' => 0,
        ]);
    }

    public function stockBaixo(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantidade' => 1,
        ]);
    }

    public function disponivel(): static
    {
        return $this->state(fn (array $attributes) => [
            'quantidade' => $this->faker->numberBetween(1, 20),
        ]);
    }
}