<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment() == 'local') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $this->call(RolesSeeder::class);
            $this->call(UsersSeeder::class);
            $this->call(SettingSeeder::class);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
        if (app()->environment() == 'production') {
            $this->call(RolesProductionSeeder::class);
            $this->call(UsersProductionSeeder::class);

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
