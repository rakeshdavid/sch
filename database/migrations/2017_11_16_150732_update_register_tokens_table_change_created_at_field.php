<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRegisterTokensTableChangeCreatedAtField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('register_tokens', function (Blueprint $table) {
            $table->dropColumn('created_at');
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
            $table->timestamp('created_at');
        });
    }
}
