<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = array(
            array('username' => 'admin','email' => 'admin@gmail.com', 'password' => '$2y$10$mHX0f2oIMfqSnVmV9kiKZeHQM0QgiFLO./pQXxMCkuetisYuBYZ7i', 'email_verified_at' => Carbon::now(), 'role' => 1),
        );
        DB::table('users')->insert($admin);
    }
}
