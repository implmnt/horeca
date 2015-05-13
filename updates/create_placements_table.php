<?php namespace Macrobit\FoodCatalog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePlacementsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_foodcatalog_placements', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->json('properties');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_foodcatalog_placements');
    }

}
