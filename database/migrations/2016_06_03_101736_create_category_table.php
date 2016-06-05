<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name');
            $table->string('category_slug');
            $table->string('category_description');
            $table->string('category_color', 6)->default('ffffff');
            $table->integer('category_place')->default(1);
            $table->text('category_tags')->default(NULL)->nullable();
            $table->string('disallowed_usergroup')->default('4');//0 means guest.
            $table->string('allow_posting')->default('*');//Allow groups to post in the category.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('category');
    }
}
