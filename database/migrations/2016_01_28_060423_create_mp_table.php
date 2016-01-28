<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('media_platform', function (Blueprint $table) {
            $table->increments('id');
            $table->string('appname');
            $table->string('appid');
            $table->string('appsecret');
            $table->string('access_token');
            $table->timestamp('token_updated');
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
        Schema::drop('media_platform');
    }
}
