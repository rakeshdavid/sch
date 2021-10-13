<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('video_id');
            $table->integer('coach_id');
            $table->integer('user_id');

            $table->string('stripe_transaction_id');
            $table->integer('amount');
            $table->string('stripe_balance_transaction');
            $table->string('stripe_destination');
            $table->string('stripe_destination_payment');

            $table->string('status',25);
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
        Schema::drop('transactions');
    }
}
