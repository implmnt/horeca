<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Macrobit\FoodCatalog\Models\Firm;
use BackendAuth;
use ApplicationException;

/**
 * Firms Back-end Controller
 */
class Firms extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $requiredPermissions = ['macrobit.foodcatalog.access_manage_firms', 'macrobit.foodcatalog.access_firms'];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'firms');

    }

    public function listExtendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms'])) {
            $query->whereHas('users', function($q)
            {
                $q->where('id', '=', $this->user->id);
            });  
       }
    }    

    public function formExtendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms'])) {
            $query->whereHas('users', function($q)
            {
                $q->where('id', '=', $this->user->id);
            });  
       }
    }

}