<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'User1', 
            'email' => 'aaa@aaa.hu', 
            'password' => bcrypt('aaaaaaaa'), 
        ]);
        DB::table('users')->insert([
            'name' => 'admin', 
            'email' => 'bbb@bbb', 
            'password' => bcrypt('bbbbbbbb'), 
            'role' => 1, 
        ]);
    }
}
