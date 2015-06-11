<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateNodesTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_nodes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('firm_id')->unsigned()->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('nest_left')->nullable();
            $table->integer('nest_right')->nullable();
            $table->integer('nest_depth')->nullable();
            $table->timestamps();
        });

        Schema::create('macrobit_horeca_node_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('tag_id')->unsigned();
            $table->integer('node_id')->unsigned();
            $table->primary(['node_id', 'tag_id']);
        });

    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_nodes');
        Schema::dropIfExists('macrobit_horeca_node_tags');
    }

}
