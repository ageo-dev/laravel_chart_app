<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Admin::create([
            'name' => 'admin',
            'password' => Hash::make('test12345'),
            'remember_token' => str_random(10),
            'role_id' => 1,
            'memo' => ''
        ]);

        // factory(App\Admin::class, 20)->create();
    }
}
