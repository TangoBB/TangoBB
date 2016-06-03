<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
    		'name' => 'admin',
    		'email' => 'admin@site.com',
    		'password' => Hash::make('admin'),
            'group' => 1
    		]);
    }
}
