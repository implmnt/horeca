<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Prices Back-end Controller
 */
class Prices extends Controller
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

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'prices');
    }
    
}