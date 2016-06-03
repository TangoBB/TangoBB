<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('post')->insert([
        	'post_name' => 'First Thread',
        	'post_content' => 'First thread contents.',
        	'post_slug' => 'first_thread',
        	'category_id' => 1,
        	'post_type' => 1,
        	'posted_by' => 1,
    		]);
    }
}
