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
        $this->call(UsersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(AppConfigsTableSeeder::class);
        $this->call(RanksTableSeeder::class);
        $this->call(RolesTableSeeder::class);
    }
}
