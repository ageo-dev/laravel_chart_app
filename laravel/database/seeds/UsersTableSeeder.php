<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $fund = 10000;
        // $date = new Carbon(Carbon::now()->format('Y-m-d'));
        // $date = new Carbon('2018-12-01');
        // App\User::create([
        //     'name' => 'admin',
        //     'email' => 'admin@admin.com',
        //     'password' => Hash::make('test12345'),
        //     'rank_id' => 1,
        //     'remember_token' => str_random(10),
        //     'custom_per' => 13,
        //     'custom_per_flag' => true,
        //     'first_fund' => $fund,
        //     'total_fund'=> $fund,
        //     'now_fund' => $fund,
        //     'start_date' => $date,
        //     'log_done_date' => $date,
        //     'memo' => ''
        // ]);

        // factory(App\User::class, 10)->create();
    }
}
