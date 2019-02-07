<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedInteger('rank_id')->default(1);
            $table->double('custom_per',20,3)->default(0);
            $table->boolean('custom_per_flag')->default(false);
            $table->double('first_fund',20,3)->default(0);
            $table->double('total_fund',20,3)->default(0);
            $table->double('now_fund',20,3)->default(0);
            $table->unsignedInteger('investment_count')->default(1);
            $table->dateTime('start_date')->default(new Carbon(Carbon::now()->format('Y-m-d')));
            $table->dateTime('log_done_date')->default(new Carbon(Carbon::now()->format('Y-m-d')));
            $table->text('memo');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
