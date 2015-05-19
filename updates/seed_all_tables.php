<?php namespace Macrobit\Demo\Updates;

use October\Rain\Database\Updates\Seeder;
use Macrobit\FoodCatalog\Models\Tag;

class SeedAllTables extends Seeder
{
    public function run()
    {
        Tag::insert([
            ['type' => 'price', 'name' => 'Закуски'],
            ['type' => 'price', 'name' => 'Салаты'],
            ['type' => 'price', 'name' => 'Напитки'],
            ['type' => 'price', 'name' => 'Вегетарианское'],
            ['type' => 'price', 'name' => 'Газированные'],
            ['type' => 'price', 'name' => 'Диетические'],
            ['type' => 'firm', 'name' => 'Ресторан'],
            ['type' => 'firm', 'name' => 'Кафе'],
            ['type' => 'firm', 'name' => 'Бар'],
            ['type' => 'firm', 'name' => 'Клуб']
        ]);
    }
}
