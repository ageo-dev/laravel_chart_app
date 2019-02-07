<?php

use Illuminate\Database\Seeder;

class AppConfigsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\AppConfig::create([
            'default_per' => 10,
            'update_hour' => 10,
            'update_minute' => 30,
            'list_paginate' => 10,
        ]);

    }
}
