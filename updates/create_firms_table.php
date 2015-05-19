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
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->string('address');
            $table->string('phone');
            $table->string('url');
            $table->timestamps();
        });

        Schema::table('backend_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('firm_id')->unsigned()->nullable();
        });

    }

    public function down()
    {
        Schema::dropIfExists('macrobit_foodcatalog_firms');
        Schema::table('backend_users', function($table)
        {
            $table->dropColumn('firm_id');
        });
    }

}