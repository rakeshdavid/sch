<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
            $table->tinyInteger('visible')->default(1);
            $table->enum('type', ['video', 'image'])->default('image');
            $table->integer('user_id')->unsigned()->index();
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
        Schema::drop('coach_galleries');
    }
}
