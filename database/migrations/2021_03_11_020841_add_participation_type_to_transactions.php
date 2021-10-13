<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParticipationTypeToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('participation_type',['V','C','A'])
                ->after('user_id')
                ->default('V')
                ->comment('Column video_id: V=>videos.id, C=>challenge_participant.id, A=>audition_participant.id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('participation_type');
        });
    }
}
