<?php namespace Macrobit\FoodCatalog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePricesTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_foodcatalog_prices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('firm_id')->unsigned();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('ingredients')->nullable();
            $table->string('portion')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_sale')->default(false);
            $table->integer('cost')->nullable();
            $table->timestamps();
        });

        Schema::create('macrobit_foodcatalog_price_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('tag_id')->unsigned();
            $table->integer('price_id')->unsigned();
            $table->primary(['price_id', 'tag_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_foodcatalog_prices');
        Schema::dropIfExists('macrobit_foodcatalog_price_tags');
    }

}
