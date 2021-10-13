<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPerformanceLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_performance_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('performance_level_id')->unsigned()->index();

            $table->unique(['user_id', 'performance_level_id']);
            /*$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('performance_level_id')->references('id')->on('performance_levels')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_performance_levels');
    }
}
