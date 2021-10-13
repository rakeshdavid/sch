<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    private $_table_name = 'proposals';
    
    public function up()
    {
        Schema::create($this->_table_name, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('video_id');
            $table->string('description');
            $table->tinyInteger('status');
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
