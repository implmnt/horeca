<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTablesTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_tables', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('placement_id');
            $table->string('name');
            $table->string('position');
            $table->integer('status')->default(0);
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_tables');
    }

}