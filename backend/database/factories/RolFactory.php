<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\rol>
 */
class RolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>fake()->unique()->randomElement(['Admin',"Management","Marketing"]),
            'create_by'=> User::inRandomOrder()->first()->id,    
            'update_by'=> User::inRandomOrder()->first()->id   
        ];
    }
}
