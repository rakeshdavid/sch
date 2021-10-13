<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoachConectedData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stripe_connections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('access_token',100);
            $table->string('refresh_token',100);
            $table->string('token_type',100);
            $table->string('stripe_publishable_key',100);
            $table->string('stripe_user_id',100);
            $table->string('scope',100);
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
        Schema::drop('coach_connect_datas');
    }
}
