<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Person>
 */
class PersonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'phone' => fake()->phoneNumber(),
            'born' => fake()->date(), 
            'create_by'=> optional(User::inRandomOrder()->first())->id,   
            'update_by'=> optional(User::inRandomOrder()->first())->id


        ];
    }
}
