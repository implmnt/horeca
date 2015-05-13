<?php namespace Macrobit\FoodCatalog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTablesTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_foodcatalog_tables', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('placement_id');
            $table->string('name');
            $table->json('properties');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_foodcatalog_tables');
    }

}
