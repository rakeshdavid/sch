<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToAuditionParticipant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audition_participant', function (Blueprint $table) {
            $table->boolean('status')
                ->after('thumbnail_url')
                ->default(0)
                ->comment('0=>not reviewed, 1=>reviewed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audition_participant', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
