<?php namespace Macrobit\Horeca\Updates;

use DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateCommentsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_comments', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('firm_id')->unsigned()->nullable();
            $table->integer('price_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('content')->nullable();
            $table->integer('state')->default(0);
            $table->integer('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_comments');
    }

}
