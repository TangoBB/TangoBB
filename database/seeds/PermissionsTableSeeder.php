<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('permission')->insert([
        	['permission_name' => 'post.view'],
        	['permission_name' => 'post.reply'],
        	['permission_name' => 'post.create'],
            ['permission_name' => 'post.edit'],
        	['permission_name' => 'account.login'],
        	['permission_name' => 'account.change.password'],
        	['permission_name' => 'account.change.email'],
        	['permission_name' => 'admin.access'],
        	['permission_name' => 'moderator.access'],
        	['permission_name' => 'moderator.delete.post'],
        	['permission_name' => 'moderator.delete.user'],
        	['permission_name' => 'moderator.user.ban'],
        	['permission_name' => 'moderator.edit.post'],
            ['permission_name' => 'moderator.sticky.post'],
            ['permission_name' => 'moderator.lock.post']
    		]);
    }
}
