<?php

use Illuminate\Database\Seeder;

class add_log_type extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('logs_types')->where('name', 'mail')->delete();

        DB::table('logs_types')->insert([
            ['name' => 'mail'],
        ]);
    }
}
