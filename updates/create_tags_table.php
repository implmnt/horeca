<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTagsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('type')->index();
            $table->timestamps();
        });

    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_tags');
    }

}
