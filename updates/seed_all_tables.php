<?php namespace Macrobit\Horeca\Updates;

use October\Rain\Database\Updates\Seeder;
use Macrobit\Horeca\Models\Tag as TagModel;

class SeedAllTables extends Seeder
{
    public function run()
    {
        TagModel::insert([
            /**
             * Type price
             */
            [
                'type' => 'price', 
                'name' => 'Закуски'
            ],
            [
                'type' => 'price', 
                'name' => 'Салаты'
            ],
            [
                'type' => 'price', 
                'name' => 'Напитки'
            ],
            [
                'type' => 'price', 
                'name' => 'Вегетарианское'
            ],
            [
                'type' => 'price', 
                'name' => 'Газированные'
            ],
            [
                'type' => 'price', 
                'name' => 'Диетические'
            ],
            /**
             * Type firm
             */
            [
                'type' => 'firm', 
                'name' => 'Ресторан'
            ],
            [
                'type' => 'firm', 
                'name' => 'Кафе'
            ],
            [
                'type' => 'firm', 
                'name' => 'Бар'
            ]
            ,
            [
                'type' => 'firm', 
                'name' => 'Клуб'
            ]
        ]);
    }
}
