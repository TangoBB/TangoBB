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
            $table->string('allowed_usergroup')->default('*');
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
