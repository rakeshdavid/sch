<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParticipationTypeToPaymentsLists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments_lists', function (Blueprint $table) {
            $table->enum('participation_type',['V','C','A'])
                ->after('video_id')
                ->default('V')
                ->comment('Column video_id: viVdeo=>videos.id, C=>challenge_participant.id, A=>audition_participant.id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments_lists', function (Blueprint $table) {
            $table->dropColumn('participation_type');
        });
    }
}
