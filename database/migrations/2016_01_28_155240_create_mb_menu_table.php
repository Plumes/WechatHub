<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMbMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('mp_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('mp_id');
            $table->string('name');
            $table->string('type');
            $table->string('key');
            $table->string('url');
            $table->smallInteger('order');
            $table->integer('parent');

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
        Schema::drop('mp_menu');
    }
}
