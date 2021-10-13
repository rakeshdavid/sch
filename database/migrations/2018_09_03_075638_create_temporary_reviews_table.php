<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temporary_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('video_id');
            $table->boolean('video_processed_status')->default(0);
            $table->string('url');
            $table->string('review_url')->nullable()->default(null);
            $table->text('play_time');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('temporary_reviews');
    }
}
