<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequisicaoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'livro_id' => Livro::factory(),
            'data_inicio' => now(),
            'data_fim' => now()->addDays(14),
            'status' => $this->faker->randomElement(['pendente', 'aprovada', 'rejeitada', 'devolvida']),
        ];
    }
    
    public function aprovada(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aprovada',
        ]);
    }

    public function pendente(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pendente',
        ]);
    }

    public function devolvida(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'devolvida',
        ]);
    }
}