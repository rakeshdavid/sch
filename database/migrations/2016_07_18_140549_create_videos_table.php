<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    private $_table_name = 'videos';
    
    public function up()
    {
        Schema::create($this->_table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name');
            $table->string('description');
            $table->string('url');
            $table->string('genres');
            $table->tinyInteger('level');
            $table->tinyInteger('status');// 1-new; 2-accept proposal; 3-reviwed
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
