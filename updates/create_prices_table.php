<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePricesTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_prices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('firm_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('ingredients')->nullable();
            $table->string('portion')->nullable();
            $table->boolean('is_new')->default(false);
            $table->boolean('is_sale')->default(false);
            $table->string('cost')->nullable();
            $table->timestamps();
        });

        Schema::create('macrobit_horeca_price_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('tag_id')->unsigned();
            $table->integer('price_id')->unsigned();
            $table->primary(['price_id', 'tag_id']);
        });

    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_prices');
        Schema::dropIfExists('macrobit_horeca_price_tags');
    }

}
