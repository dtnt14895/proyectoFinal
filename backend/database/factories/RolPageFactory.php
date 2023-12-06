<?php

namespace Database\Factories;

use App\Models\Page;
use App\Models\Person;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rolpagege>
 */
class RolPageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'name' => fake()->company(),
            'enlaced_to'=>null,
            'page_id'=>null,
            'rol_id'=>Rol::inRandomOrder()->first()->id,
            'order'=>fake()->randomElement(['1', '2', '3', '4', '5', '6']),
            'create_by'=> User::inRandomOrder()->first()->id,     
            'update_by'=> User::inRandomOrder()->first()->id,  
            
            


        ];
    }
}
