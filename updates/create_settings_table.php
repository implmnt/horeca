<?php namespace Macrobit\Horeca\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Macrobit\Horeca\Models\Settings;

class CreateTablesTable extends Migration
{

    public function up()
    {
        $this->down();
        Schema::create('macrobit_horeca_settings', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('id')->unsigned()->default(1)->primary();
            $table->integer('default_payment');
            $table->timestamps();
        });

        Schema::create('macrobit_horeca_settings_paymentmethods', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('settings')->unsigned()->nullable();
            $table->integer('payment')->unsigned()->nullable();
        });

        Schema::create('macrobit_horeca_settings_events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('settings')->unsigned()->nullable();
            $table->integer('event')->unsigned()->nullable();
        });

        Schema::create('macrobit_horeca_settings_slider', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('settings')->unsigned()->nullable();
            $table->integer('event')->unsigned()->nullable();
        });

        $this->seedDefaultSettings();
    }

    public function down()
    {
        Schema::dropIfExists('macrobit_horeca_settings');
        Schema::dropIfExists('macrobit_horeca_settings_paymentmethods');
        Schema::dropIfExists('macrobit_horeca_settings_events');
        Schema::dropIfExists('macrobit_horeca_settings_slider');
    }

    private function seedDefaultSettings()
    {
        Settings::insert([
            'id' => 1
        ]);
    }

}