<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->delete();
        $categories = array(
            array('id'=> 1, 'name' => 'MMA', 'img'=> 'https://cdn.discordapp.com/attachments/1108287039113281557/1110117541902295082/OIP.jfif', 'show' => 1),
            array('id'=> 2, 'name' => 'HIIT', 'img'=> 'https://images.contentstack.io/v3/assets/blt45c082eaf9747747/blta585249cb277b1c3/5fdcfa83a703d10ab87e827b/HIIT.jpg?format=pjpg&auto=webp&quality=76&width=1232', 'show' => 1),
            array('id'=> 3, 'name' => 'Just Move', 'img'=> 'https://cdn.discordapp.com/attachments/1108287039113281557/1110117542393040916/R.jfif', 'show' => 1),
            array('id'=> 4, 'name' => 'Strength', 'img'=> 'https://cdn.discordapp.com/attachments/1108287039113281557/1110117540929212477/OIP_1.jfif', 'show' => 1),
            array('id'=> 5, 'name' => 'Yoga', 'img'=> 'https://cdn.discordapp.com/attachments/1108287039113281557/1110117541294120981/OIP_2.jfif', 'show' => 1),
            array('id'=> 6, 'name' => 'Low Impact', 'img'=> 'https://cdn.discordapp.com/attachments/1108287039113281557/1110117541579329586/OIP_3.jfif', 'show' => 1),
        );
        DB::table('categories')->insert($categories);
    }
}
