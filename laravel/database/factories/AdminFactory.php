<?php

use Faker\Generator as Faker;

$factory->define(App\Admin::class, function (Faker $faker) {
    static $password;
    static $seed = 1;
    $faker->seed($seed++);

    return [
        'name' => $faker->name,
        'password' => $password ?: $password = bcrypt('test12345'),
        'role_id' => $faker->numberBetween(1,4),
        'memo' => '',
        'remember_token' => str_random(10),
    ];
});
