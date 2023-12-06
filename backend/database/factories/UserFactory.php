<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          
            'person_id'=> Person::inRandomOrder()->first()->id,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' =>'admin', // password
            'remember_token' => Str::random(10),
            'rol_id'=>  optional(Rol::inRandomOrder()->first())->id,      
            'create_by'=> 1,      
            'update_by'=> 1    
          
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
