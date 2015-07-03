<?php namespace Macrobit\Horeca\Updates;

use DB;
use Schema;
use October\Rain\Database\Updates\Migration;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_orders', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('firm_id')->unsigned()->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('comment')->nullable();
            $table->string('fullname')->nullable();
            $table->integer('state')->default(0)->nullable();
            $table->timestamps();
        });

        Schema::create('macrobit_horeca_order_prices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('price_id')->unsigned()->nullable();
            $table->integer('amount')->default(0);
            $table->primary(['order_id', 'price_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_orders');
        Schema::dropIfExists('macrobit_horeca_order_prices');
    }

}
