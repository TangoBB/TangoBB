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
        //
    	DB::table('settings')->insert([
    		'forum_name' => 'Forum',
    		'forum_theme' => 'default'
    		]);
    }
}
