<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('settings')->insert([
        	'forum_name' => 'Forum',
        	'forum_theme' => 'default'
        	]);
    }
}
