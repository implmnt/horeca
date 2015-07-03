<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Baskets Back-end Controller
 */
class Baskets extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'baskets');
    }
}