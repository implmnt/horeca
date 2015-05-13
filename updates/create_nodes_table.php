<?php namespace Macrobit\FoodCatalog\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateNodesTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_foodcatalog_nodes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();

            // Fields for \October\Rain\Database\Traits\NestedTree
            $table->integer('parent_id')->default(0)->unsigned()->index();
            $table->integer('nest_left')->default(0);
            $table->integer('nest_right')->default(0);
            $table->integer('nest_depth')->default(0);

        });

        Schema::create('macrobit_foodcatalog_node_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('tag_id')->unsigned();
            $table->integer('node_id')->unsigned();
            $table->primary(['node_id', 'tag_id']);
        });

        Schema::create('macrobit_foodcatalog_node_firms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('firm_id')->unsigned();
            $table->integer('node_id')->unsigned();
            $table->primary(['node_id', 'firm_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_foodcatalog_nodes');
        Schema::dropIfExists('macrobit_foodcatalog_node_tags');
        Schema::dropIfExists('macrobit_foodcatalog_node_firms');
    }

}
