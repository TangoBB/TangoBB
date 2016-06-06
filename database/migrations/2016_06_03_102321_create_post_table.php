<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->increments('id');
            $table->string('post_name');
            $table->text('post_content');
            $table->string('post_slug');
            $table->integer('category_id')->default(NULL)->nullable();
            $table->integer('post_type')->default(1);//1 for thread starter. 2 for post.
            $table->integer('post_id')->default(NULL)->nullable();
            $table->integer('is_locked')->default(0);
            $table->integer('is_stickied')->default(0);
            $table->integer('posted_by');
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
        Schema::drop('post');
    }
}
