<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      if(!DB::table('settings')->where('key','platform_maintenance_mode')->exists()){
         DB::table('settings')->insert([
            'name' => 'Platform maintenance mode',
            'type' => 'checkbox',
            'key'  => 'platform_maintenance_mode',
            'value' => 0
        ]);
      }
      if(!DB::table('settings')->where('key','coaches_maintenance_mode')->exists()){
         DB::table('settings')->insert([
            'name' => 'Coaches maintenance mode',
            'type' => 'checkbox',
            'key'  => 'coaches_maintenance_mode',
            'value' => 0
        ]);
      }
      if(!DB::table('settings')->where('key','coach_commission_reviewing_video')->exists()){
         DB::table('settings')->insert([
               'name' => 'Coach commission for reviewing video in percentage',
               'type' => 'number',
               'key'  => 'coach_commission_reviewing_video',
               'value' => 20
         ]);
      }
      if(!DB::table('settings')->where('key','coach_commission_reviewing_challenge')->exists()){
         DB::table('settings')->insert([
               'name' => 'Coach commission for reviewing challenge in percentage',
               'type' => 'number',
               'key'  => 'coach_commission_reviewing_challenge',
               'value' => 20
         ]);
      }
      if(!DB::table('settings')->where('key','agency_commission_reviewing_audition')->exists()){
         DB::table('settings')->insert([
               'name' => 'Agency commission for reviewing audition in percentage',
               'type' => 'number',
               'key'  => 'agency_commission_reviewing_audition',
               'value' => 20
         ]);
      }
    }
}
