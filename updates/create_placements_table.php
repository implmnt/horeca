<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePlacementsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_placements', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('firm_id')->unsigned()->nullable();
            $table->string('name');
            $table->integer('height')->default('300');
            $table->integer('width')->default('500');
            $table->string('tables')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_placements');
    }

}
