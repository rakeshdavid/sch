<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    private $_table_name = 'users';
    
    public function up()
    {
        Schema::create($this->_table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('facebook_id')->unique();
            $table->string('avatar');
            $table->string('baner');
            $table->string('gender');
            $table->string('genres');
            $table->tinyInteger('level');
            $table->string('birthday');
            $table->string('languages');
            $table->string('location');
            $table->string('activities');
            $table->text('about');
            $table->string('phone');
            $table->string('wevsites');
            $table->string('social_links');
            $table->tinyInteger('role');
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
        Schema::drop($this->_table_name);
    }
}
