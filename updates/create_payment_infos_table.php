<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePaymentInfosTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_payment_infos', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('order_id')->unsigned()->nullable();
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->integer('state')->default(0)->nullable();
            $table->integer('total')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_payment_infos');
    }

}
