<?php namespace Macrobit\FoodCatalog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateFirmsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_foodcatalog_firms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });

        Schema::create('macrobit_foodcatalog_firms_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('firm_id');
            $table->integer('user_id');
        });

    }

    public function down()
    {
        Schema::dropIfExists('macrobit_foodcatalog_firms_users');
        Schema::dropIfExists('macrobit_foodcatalog_firms');
    }

}