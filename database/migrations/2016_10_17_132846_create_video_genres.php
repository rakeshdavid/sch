<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoGenres extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_id')->unsigned()->index();
            $table->integer('activity_genre_id')->unsigned()->index();

            $table->unique(['video_id', 'activity_genre_id']);
            /*$table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
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
        Schema::drop('video_genres');
    }
}
