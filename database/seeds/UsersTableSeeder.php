<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'role_id' => 1,
            'name' => 'Md.Admin',
            'username' => 'admin',
            'email' => 'admin@mail.com',
            'password' => bcrypt('bangladesh')
        ]);

        DB::table('users')->insert([
            'role_id' => 2,
            'name' => 'Md.Author',
            'username' => 'author',
            'email' => 'author@mail.com',
            'password' => bcrypt('bangladesh')
        ]);
    }
}
