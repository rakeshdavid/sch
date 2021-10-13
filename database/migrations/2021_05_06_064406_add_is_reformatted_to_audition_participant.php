<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReformattedToAuditionParticipant extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audition_participant', function (Blueprint $table) {
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
        Schema::table('audition_participant', function (Blueprint $table) {
            $table->dropColumn('is_reformatted');
        });
    }
}
