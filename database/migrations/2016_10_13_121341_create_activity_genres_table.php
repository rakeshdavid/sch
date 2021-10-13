<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityGenresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_genres', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_type_id')->unsigned()->index();
            $table->string('name');

            $table->foreign('activity_type_id')->references('id')->on('activity_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activity_genres');
    }
}
