<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddThumbnailAndReformattedStatusToVideos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('thumbnail')
                ->nullable()
                ->after('url')
                ->default(null);
            $table->boolean('is_reformatted')->default(0);
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
            $table->dropColumn('thumbnail');
            $table->dropColumn('is_reformatted');
        });
    }
}
