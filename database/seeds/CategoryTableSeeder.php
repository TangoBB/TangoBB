<?php

use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('category')->insert([
    		'category_name' => 'First Category',
    		'category_slug' => 'first_category',
    		'category_description' => 'First category description.',
            'category_color' => '414141'
    		]);
    }
}
