<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_id');
            $table->string('news_media_id');
            $table->string('thumb_media_id');
            $table->string('author');
            $table->string('title');
            $table->string('content_source_url');
            $table->text('content');
            $table->string('digest');
            $table->string('url');
            $table->string('thumb_url');
            $table->tinyInteger('show_cover_pic');
            $table->smallInteger('order');
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
        //
        Schema::drop('articles');
    }
}
