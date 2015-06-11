<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreatePaymentMethodsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_payment_methods', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_payment_methods');
    }

}
