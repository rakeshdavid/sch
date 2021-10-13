<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('timing_comment', 500)->change();
            $table->string('footwork_comment', 500)->change();
            $table->string('alingment_comment', 500)->change();
            $table->string('balance_comment', 500)->change();
            $table->string('focus_comment', 500)->change();
            $table->string('precision_comment', 500)->change();
            $table->string('energy_comment', 500)->change();
            $table->string('style_comment', 500)->change();
            $table->string('creativity_comment', 500)->change();
            $table->string('interpretation_comment', 500)->change();
            $table->string('formation_comment', 500)->change();
            $table->string('artisty_comment', 500)->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
