<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('video_id');
            $table->string('question');
            $table->text('answer')->nullable();
            $table->tinyInteger('question_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('review_questions');
    }
}
