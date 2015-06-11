<?php namespace Macrobit\Horeca\Updates;

use Schema, DB;
use October\Rain\Database\Updates\Migration;

class CreateEventsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('firm_id')->unsigned()->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_events');
    }

}
