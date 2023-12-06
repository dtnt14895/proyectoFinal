<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'description' => fake()->text($maxNbChars = 50),
            'user_id'=>User::inRandomOrder()->first()->id,
            'ip' => fake()->ipv4(), 
            'so' =>fake()->randomElement(['Microsoft Windows', 'GNU/Linux','MacOS','Android ','iOS']),
            'browser'=>fake()->randomElement(['Mozilla/5.0', 'Chrome/83.0.4808.69','Opera/8.34','Brave','Microsoft Edge','Microsoft Edge']),
        ];
    }
}
