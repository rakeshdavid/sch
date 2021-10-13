<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReviewComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('status_comment')->nullable()->after('status');
            $table->string('artisty_comment')->nullable()->after('artisty');
            $table->string('formation_comment')->nullable()->after('formation');
            $table->string('interpretation_comment')->nullable()->after('interpretation');
            $table->string('creativity_comment')->nullable()->after('creativity');
            $table->string('style_comment')->nullable()->after('style');
            $table->string('energy_comment')->nullable()->after('energy');
            $table->string('precision_comment')->nullable()->after('precision');
            $table->string('timing_comment')->nullable()->after('timing');
            $table->string('footwork_comment')->nullable()->after('footwork');
            $table->string('alingment_comment')->nullable()->after('alingment');
            $table->string('balance_comment')->nullable()->after('balance');
            $table->string('focus_comment')->nullable()->after('focus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
        });
    }
}
