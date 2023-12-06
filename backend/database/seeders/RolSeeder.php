<?php

namespace Database\Seeders;

use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Rol::factory(3)->create();

        Rol::factory()->create([
            'name'=>'Admin',
            'create_by'=> 1,      
            'update_by'=> 1 
        ]);
        Rol::factory()->create([
            'name'=>'Management',
            'create_by'=> 1,      
            'update_by'=> 1 
        ]);
        Rol::factory()->create([
            'name'=>'Marketing',
            'create_by'=> 1,      
            'update_by'=> 1 
        ]);

    }
}
