<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'name' => 'admin',
            'email' => 'admin@163.com',
            'password' => bcrypt('123456'),
            'role_level' => 9,
            'remember_token' => str_random(10),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'username' => 'aaa',
            'name' => 'aaa',
            'email' => 'aaa@163.com',
            'password' => bcrypt('123456'),
            'role_level' => 1,
            'remember_token' => str_random(10),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        DB::table('users')->insert([
            'username' => 'bbb',
            'name' => 'bbb',
            'email' => 'bbb@163.com',
            'password' => bcrypt('123456'),
            'role_level' => 1,
            'remember_token' => str_random(10),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        $this->command->info("admin账户. Username: admin,  Password: 123456");
    }
}
