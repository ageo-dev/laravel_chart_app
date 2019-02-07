<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;
    static $seed = 0;
    $faker->seed($seed++);

    $rank_id = $faker->numberBetween(1,6);
    $fund = $faker->numberBetween(5000,100000);
    $date =  $faker->dateTimeThisMonth();
    // $date =  '2018-12-17';
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('test12345'),
        'rank_id' => $rank_id,
        'remember_token' => str_random(10),
        'custom_per' => $faker->numberBetween(0,30),
        'custom_per_flag' => $faker->numberBetween(0,1),
        'first_fund' => $fund,
        'total_fund' => $fund,
        'now_fund' => $fund,
        'start_date' => $date,
        'log_done_date' => $date,
        'memo' => ''
    ];
});
