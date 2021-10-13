<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->tinyInteger('activity_experience')->default(1)->after('url');
            $table->enum('seeking_auditions', ['yes', 'no', 'maybe', 'not_yet'])->default('no')->after('description');
            $table->integer('package_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            //
        });
    }
}
