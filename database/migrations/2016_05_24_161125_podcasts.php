<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Podcasts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('podcasts', function(Blueprint $table)
      {
        $table->increments('id');
        $table->integer('program_id')->unsigned();
        $table->string('title');
        $table->text('description');
        $table->string('link');
        $table->integer('duration')->unsigned();
        $table->timestamp('date');
        $table->timestamps();

        $table->unique( array('date','program_id') );
        $table->foreign('program_id')
              ->references('id')->on('programs')
              ->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('podcasts');
    }
}
