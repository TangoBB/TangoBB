<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('group')->insert(
            ['group_name' => 'Admin', 'group_style' => '<span style="font-color:#e0b346;">%username%</span>']
            );
        DB::table('group')->insert(
            ['group_name' => 'Moderator', 'group_style' => '<span style="font-color:#f48567;">%username%</span>', 'group_permissions' => '1,2,3,4,5,6,8,9,10,11,12,13,14,15']
            );
        DB::table('group')->insert(
            ['group_name' => 'User', 'group_style' => '%username%', 'group_permissions' => '1,2,3,4,5,6, 7']
            );
        DB::table('group')->insert(
            ['group_name' => 'Banned', 'group_style' => '<span style="text-decoration: line-through;">%username%</span>', 'group_permissions' => NULL]
            );
    }
}
