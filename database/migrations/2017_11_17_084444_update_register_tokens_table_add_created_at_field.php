<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRegisterTokensTableAddCreatedAtField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tokens', function (Blueprint $table) {
            $table->dateTime('created_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('register_tokens', function (Blueprint $table) {
            $table->dropColumn('created_at');
        });
    }
}
