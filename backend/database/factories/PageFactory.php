<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

            'url'=> fake()->url(),
            'name' => fake()->domainName(),
            'description'=>fake()->text($maxNbChars = 75),
            'icon' => fake()->jobTitle(),
            'type' => fake()->domainWord(),
            'create_by'=> User::inRandomOrder()->first()->id,      
            'update_by'=> User::inRandomOrder()->first()->id  
        ];
    }
}
