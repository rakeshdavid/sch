<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReformattedToChallengeParticipant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('challenge_participant', function (Blueprint $table) {
            $table->boolean('is_reformatted')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('challenge_participant', function (Blueprint $table) {
            $table->dropColumn('is_reformatted');
        });
    }
}
