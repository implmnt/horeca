<?php namespace Macrobit\Demo\Updates;

use October\Rain\Database\Updates\Seeder;
use Macrobit\FoodCatalog\Models\Tag;

class SeedAllTables extends Seeder
{
    public function run()
    {
        Tag::insert([
            ['name' => 'red'],
            ['name' => 'white'],
            ['name' => 'black'],
            ['name' => 'blue'],
            ['name' => 'green'],
            ['name' => 'yellow']
        ]);
    }
}
