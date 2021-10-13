<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('activity_genre_id')->unsigned()->index();

            $table->unique(['user_id', 'activity_genre_id']);
            /*$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('activity_genre_id')->references('id')->on('activity_genres')->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_genres');
    }
}
