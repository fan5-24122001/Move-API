<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = array(
            array('username' => 'tmthien','email' => 'thien@gmail.com', 'password' => '$2y$10$7lmmKYMBeTI1KxlKA9xwUefGYToWVW6JTT6r3PfgmplDT5x8GvkqO', 'email_verified_at' => Carbon::now(), 'role' => 0),
            array('username' => 'nam','email' => 'nam@gmail.com', 'password' => '$2y$10$7lmmKYMBeTI1KxlKA9xwUefGYToWVW6JTT6r3PfgmplDT5x8GvkqO', 'email_verified_at' => Carbon::now(), 'role' => 0),
            array('username' => 'hau','email' => 'hau@gmail.com', 'password' => '$2y$10$7lmmKYMBeTI1KxlKA9xwUefGYToWVW6JTT6r3PfgmplDT5x8GvkqO', 'email_verified_at' => Carbon::now(), 'role' => 0),
            array('username' => 'tuan','email' => 'tuan@gmail.com', 'password' => '$2y$10$7lmmKYMBeTI1KxlKA9xwUefGYToWVW6JTT6r3PfgmplDT5x8GvkqO', 'email_verified_at' => Carbon::now(), 'role' => 0),
            array('username' => 'tin','email' => 'tin@gmail.com', 'password' => '$2y$10$7lmmKYMBeTI1KxlKA9xwUefGYToWVW6JTT6r3PfgmplDT5x8GvkqO', 'email_verified_at' => Carbon::now(), 'role' => 0),
        );
        DB::table('users')->insert($user);
    }
}
