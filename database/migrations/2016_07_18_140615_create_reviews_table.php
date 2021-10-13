<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    private $_table_name = 'reviews';
    
    public function up()
    {
        Schema::create($this->_table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('video_id');
            $table->string('url');
            $table->tinyInteger('status');
            $table->tinyInteger('artisty');
            $table->tinyInteger('formation');
            $table->tinyInteger('interpretation');
            $table->tinyInteger('creativity');
            $table->tinyInteger('style');
            $table->tinyInteger('energy');
            $table->tinyInteger('precision');
            $table->tinyInteger('timing');
            $table->tinyInteger('footwork');
            $table->tinyInteger('alingment');
            $table->tinyInteger('balance');
            $table->tinyInteger('focus');
            $table->text('message');
            $table->text('play_time');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->_table_name);
    }
}
