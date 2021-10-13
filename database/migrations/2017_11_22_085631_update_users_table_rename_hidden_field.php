<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTableRenameHiddenField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('hidden');
            $table->boolean('is_hidden')->nullable(false)->defaul(0)->after('price_detailed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
            $table->boolean('hidden')->nullable(false)->defaul(0)->after('price_detailed');
        });
    }
}
