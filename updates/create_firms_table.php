<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateFirmsTable extends Migration
{

    public function up()
    {
        Schema::create('macrobit_horeca_firms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->string('email');
            $table->integer('avg_bill')->nullable();
            $table->string('day_activity_period')->nullable();
            $table->string('day_break_period')->nullable();
            $table->string('holydays')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('address');
            $table->string('phone');
            $table->string('url');
            $table->string('map_point')->default('[]');
            $table->timestamps();
        });

        Schema::create('macrobit_horeca_firm_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('tag_id')->unsigned();
            $table->integer('firm_id')->unsigned();
            $table->primary(['firm_id', 'tag_id']);
        });

        Schema::table('backend_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('firm_id')->unsigned()->nullable();
        });

    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_firms');
        Schema::dropIfExists('macrobit_horeca_firm_tags');
        if(Schema::hasColumn('backend_users', 'firm_id'))
        {
            Schema::table('backend_users', function($table)
            {
                $table->dropColumn('firm_id');
            });
        }
    }

}