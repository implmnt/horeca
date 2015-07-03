<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateBasketsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_baskets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('macrobit_horeca_basket_prices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('basket_id')->unsigned()->nullable();
            $table->integer('price_id')->unsigned()->nullable();
            $table->integer('amount')->default(0);
            $table->primary(['basket_id', 'price_id']);
        });

        Schema::table('users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('basket_id')->unsigned()->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_baskets');
        Schema::dropIfExists('macrobit_horeca_basket_prices');
        if(Schema::hasColumn('users', 'basket_id'))
        {
            Schema::table('users', function($table)
            {
                $table->dropColumn('basket_id');
            });
        }
    }

}
