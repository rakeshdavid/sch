<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_types', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('activity_type_id')->unsigned()->index();

            $table->unique(['user_id', 'activity_type_id']);
            /*$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('activity_type_id')->references('id')->on('activity_types')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_activity_types');
    }
}
