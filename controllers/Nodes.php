<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Nodes Back-end Controller
 */
class Nodes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'nodes');
    }

    public function implodeTags($value)
    {
        $val = array();
        $tags = json_decode($value);
        foreach ($tags as $tag) {
            array_push($val, $tag->name);
        }
        return implode(', ', $val);
    }

}