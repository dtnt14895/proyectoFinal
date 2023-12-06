<?php

namespace Database\Seeders;

use App\Models\Rolpage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       // Rolpage::factory(2)->create();
       
       Rolpage::factory()->create([
            'name' => "Staff",
            // 'enlaced_to'=> "",
            // 'page_id'=>"",
            'rol_id'=>"2",
            'order'=>"10",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);

        Rolpage::factory()->create([
            'name' => "Peoples",
            'enlaced_to'=> 1,
            'page_id'=>"1",
            'rol_id'=>"2",
            'order'=>"11",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);
        Rolpage::factory()->create([
            'name' => "Users",
            'enlaced_to'=> 1,
            'page_id'=>"3",
            'rol_id'=>"2",
            'order'=>"12",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);
        Rolpage::factory()->create([
            'name' => "Logs",
            'enlaced_to'=> 1,
            'page_id'=>"2",
            'rol_id'=>"2",
            'order'=>"13",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);




        Rolpage::factory()->create([
            'name' => "Social",
            // 'enlaced_to'=> "",
            // 'page_id'=>"",
            'rol_id'=>"3",
            'order'=>"20",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);

        Rolpage::factory()->create([
            'name' => "GitHub",
            'enlaced_to'=> 5,
            'page_id'=>"6",
            'rol_id'=>"3",
            'order'=>"21",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);
        Rolpage::factory()->create([
            'name' => "LinkedIn",
            'enlaced_to'=> 5,
            'page_id'=>"7",
            'rol_id'=>"3",
            'order'=>"22",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);
        Rolpage::factory()->create([
            'name' => "FreeLancer",
            'enlaced_to'=> 5,
            'page_id'=>"8",
            'rol_id'=>"3",
            'order'=>"23",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);
        Rolpage::factory()->create([
            'name' => "Users",
            'enlaced_to'=> 5,
            'page_id'=>"3",
            'rol_id'=>"3",
            'order'=>"24",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);


        Rolpage::factory()->create([
            'name' => "Projet",
            // 'enlaced_to'=> 5,
            // 'page_id'=>"3",
            'rol_id'=>"1",
            'order'=>"1",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);

        Rolpage::factory()->create([
            'name' => "Autentification",
            'enlaced_to'=> 10,
            'page_id'=>"9",
            'rol_id'=>"1",
            'order'=>"1",
            'create_by'=> 1,     
            'update_by'=> 1,  
        ]);

        



    }
}
